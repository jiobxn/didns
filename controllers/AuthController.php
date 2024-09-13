<?php

class AuthController {
    const TXT_RECORD_TTL = 300; // 5分钟

    public function adminLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            if ($this->validateAdminCredentials($username, $password)) {
                $_SESSION['admin'] = $username;
                header('Location: ' . BASE_URL . '/dns');
                exit;
            } else {
                // 登录失败，保留会话数据
                $error = '无效的用户名或密码';
            }
        }
        require __DIR__ . '/../views/auth/admin_login.php';
    }

    private function validateAdminCredentials($username, $password) {
        return $username === ADMIN_USERNAME && $password === ADMIN_PASSWORD;
    }

    public function clientLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            if ($this->validateUsername($username)) {
                session_regenerate_id(true);
                $txtRecord = $this->generateSecureTxtRecord();
                $randomSuffix = bin2hex(random_bytes(4)); // 生成8个字符的随机后缀
                $txtName = TXT_RECORD_PREFIX . '-' . $randomSuffix . '.' . $username; // 使用配置项
                $_SESSION['login_username'] = $username;
                $_SESSION['login_txt_record'] = $txtRecord;
                $_SESSION['login_txt_name'] = $txtName;
                header('Location: ' . BASE_URL . '/verify');
                exit;
            } else {
                $error = '无效的域名。请输入允许的域名或其子域名。';
            }
        }
        require __DIR__ . '/../views/auth/client_login.php';
    }

    private function generateSecureTxtRecord() {
        $randomBytes = random_bytes(16);
        $randomString = bin2hex($randomBytes);
        $expirationTime = time() + self::TXT_RECORD_TTL;
        return $randomString . '.' . $expirationTime;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            if ($this->validateUsername($username)) {
                $txtRecord = $this->generateTxtRecord();
                $_SESSION['login_username'] = $username;
                $_SESSION['login_txt_record'] = $txtRecord;
                require __DIR__ . '/../views/auth/verify.php';
                return;
            } else {
                // 登录失败，保留会话数据
                $error = '无效的用户名格式';
            }
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function verify() {
        $username = $_SESSION['login_username'] ?? '';
        $txtRecord = $_SESSION['login_txt_record'] ?? '';
        $txtName = $_SESSION['login_txt_name'] ?? '';
        
        if (empty($username) || empty($txtRecord) || empty($txtName)) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // 检查TXT记录是否过期
        list($recordString, $expirationTime) = explode('.', $txtRecord);
        if (time() > $expirationTime) {
            // TXT记录已过期，重新生成
            $txtRecord = $this->generateSecureTxtRecord();
            $_SESSION['login_txt_record'] = $txtRecord;
        }

        // 生成二维码
        $qrCodeData = json_encode([
            'type' => 'TXT',
            'name' => $txtName,
            'content' => $txtRecord
        ]);
        $qrCodeUrl = BASE_URL . '/qr-generator.php?data=' . urlencode($qrCodeData);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $forceRefresh = isset($_POST['force_refresh']);
            $verificationResult = $this->verifyTxtRecord($txtName, $txtRecord, $forceRefresh);
            if ($verificationResult['status']) {
                $_SESSION['user'] = $username;
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            } else {
                $error = $verificationResult['debug'];
            }
        }
        
        require __DIR__ . '/../views/auth/verify.php';
    }

    private function validateUsername($username) {
        $allowedDomains = ALLOWED_LOGIN_DOMAINS;
        
        if ($allowedDomains === 'ALL') {
            return filter_var($username, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
        }
        
        $domains = explode(',', $allowedDomains);
        foreach ($domains as $domain) {
            $domain = trim($domain);
            if (strcasecmp($username, $domain) === 0 || preg_match('/\.' . preg_quote($domain, '/') . '$/', $username)) {
                return true;
            }
        }
        
        return false;
    }

    private function generateTxtRecord() {
        // 这个方法不再需要，但为了兼容性可以保留
        return session_id();
    }

    private function verifyTxtRecord($txtName, $txtRecord, $forceRefresh = false) {
        $dnsServers = explode(',', VERIFY_DNS_SERVERS);
        $foundRecord = false;
        $actualRecord = '';

        foreach ($dnsServers as $dnsServer) {
            $command = "dig @$dnsServer $txtName TXT +short";
            if ($forceRefresh) {
                $command .= " +tries=1 +time=1";
            }
            $result = shell_exec($command);
            $records = explode("\n", trim($result));

            foreach ($records as $record) {
                $recordValue = trim($record, '"');
                if (!empty($recordValue)) {
                    $foundRecord = true;
                    $actualRecord = $recordValue;
                    list($recordString, $recordExpiration) = explode('.', $recordValue);
                    if ($recordString === explode('.', $txtRecord)[0] && time() < $recordExpiration) {
                        return ['status' => true, 'debug' => '验证成功'];
                    }
                    break;
                }
            }

            if ($foundRecord) {
                break;
            }
        }
        
        if ($foundRecord) {
            return ['status' => false, 'debug' => "验证失败：查询记录 $actualRecord 与添加记录 $txtRecord 不匹配"];
        } else {
            return ['status' => false, 'debug' => '验证失败：所有 DNS 服务器均未找到匹配的 TXT 记录'];
        }
    }

    public function generateQrCode() {
        if (!isset($_GET['data'])) {
            header('HTTP/1.0 400 Bad Request');
            exit('Missing data parameter');
        }

        $data = $_GET['data'];

        try {
            $qrCode = \Endroid\QrCode\QrCode::create($data)
                ->setSize(300)
                ->setMargin(10);

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);

            header('Content-Type: ' . $result->getMimeType());
            echo $result->getString();
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function logout() {
        $isAdmin = isset($_GET['admin']) && $_GET['admin'] === 'true';

        if ($isAdmin && isset($_SESSION['admin'])) {
            unset($_SESSION['admin']);
            header('Location: ' . BASE_URL . '/admin/login');
        } elseif (!$isAdmin && isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            header('Location: ' . BASE_URL . '/login');
        } else {
            // 如果没有匹配的会话，重定向到主页或者显示错误
            header('Location: ' . BASE_URL);
        }
        exit;
    }
}
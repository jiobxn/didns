<?php

require_once '../models/Dns.php';

class DnsController {
    private $dnsModel;

    public function __construct() {
        $this->dnsModel = new Dns();
    }

    public function index() {
        try {
            $records = $this->dnsModel->getAllRecords();
            $message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
            unset($_SESSION['message']);
            $mainDomain = MAIN_DOMAIN;
            require '../views/dns/index.php';
        } catch (Exception $e) {
            $error = '获取DNS记录失败：' . $e->getMessage();
            require '../views/error.php';
        }
    }

    public function add($query = []) {
        // 处理查询参数
        if (isset($query['type']) && $query['type'] === 'TXT') {
            // 自动填充参数的逻辑
        }
        $type = $_GET['type'] ?? '';
        $name = $_GET['name'] ?? '';
        $content = $_GET['content'] ?? '';
        $ttl = $_GET['ttl'] ?? 1;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? '';
            $name = $_POST['name'] ?? '';
            $content = $_POST['content'] ?? '';
            $ttl = intval($_POST['ttl'] ?? 1);

            if (empty($type) || empty($name) || empty($content)) {
                $error = '所有字段都是必填的';
            } else {
                // 确保 TXT 记录的内容包含在双引号中
                if ($type === 'TXT' && !preg_match('/^".*"$/', $content)) {
                    $content = '"' . $content . '"';
                }

                try {
                    $result = $this->dnsModel->addRecord($type, $name, $content, $ttl);
                    if ($result['success']) {
                        $_SESSION['message'] = 'DNS记录添加成功';
                        header('Location: ' . BASE_URL . '/dns');
                        exit;
                    } else {
                        $error = '添加DNS记录失败：' . ($result['errors'][0]['message'] ?? '未知错误');
                    }
                } catch (Exception $e) {
                    $error = '添加DNS记录时发生错误：' . $e->getMessage();
                }
            }
        }
        
        require '../views/dns/add.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recordId = $_POST['id'] ?? '';
            if (empty($recordId)) {
                $_SESSION['message'] = '无效的记录ID';
            } else {
                try {
                    $result = $this->dnsModel->deleteRecord($recordId);
                    if ($result['success']) {
                        $_SESSION['message'] = 'DNS记录删除成功';
                    } else {
                        $_SESSION['message'] = '删除DNS记录失败：' . ($result['errors'][0]['message'] ?? '未知错误');
                    }
                } catch (Exception $e) {
                    $_SESSION['message'] = '删除DNS记录时发生错误：' . $e->getMessage();
                }
            }
        }
        header('Location: ' . BASE_URL . '/dns');
        exit;
    }
}
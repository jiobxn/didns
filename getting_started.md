# DIDNS

## 项目概述

### 背景

在现代数字化世界中，去中心化身份（DID）技术已经成为保护用户隐私和数据安全的重要工具。然而，现有的身份管理系统通常集中在少数几个大型平台上，这些平台有时可能过度控制用户数据，并存在数据泄露的风险。传统的中心化身份验证系统也面临着隐私保护和安全性的挑战。

DIDNS 项目旨在通过结合去中心化身份（DID）技术与域名系统（DNS），提供一种新的身份管理解决方案。我们希望通过将 DID 信息集成到 DNS 记录中，赋予用户对其身份数据的完全控制，同时确保数据的安全性和隐私保护。

### 目标

本项目的主要目标是创建一个去中心化的身份管理系统，利用 DNS 技术来存储和管理 DID 信息。项目的具体目标包括：

- 提供一个去中心化的身份管理解决方案，确保用户对其身份数据的完全控制。
- 利用 DNS 记录实现 DID 信息的存储和管理，提高系统的透明性和可靠性。
- 通过加密技术保护用户身份数据的安全，防止未经授权的访问和泄露。
- 为开发者和企业提供易于集成的 API，简化 DID 功能的应用。

### 项目理解与改进建议

#### 项目理解

1. **提高效率**：
   - 通过在区块链上只记录用户的ID和域名，减少了区块链的存储和计算负担。
   - DNS 记录作为登录凭证，可以快速验证用户身份，而不需要频繁访问区块链。

2. **用户控制**：
   - 用户可以完全控制其域名和 DNS 记录，确保数据的自主权。
   - 结合 DID 技术，用户可以在不同平台上使用同一身份，增强了互操作性。

3. **安全性**：
   - 区块链上的记录具有不可篡改性，确保用户ID和域名的真实性。
   - DNS 记录可以通过加密技术保护，防止未经授权的访问。

#### 改进建议

1. **启用 DNSSEC**：
   - 确保你的 DNS 服务器和域名注册商支持 DNSSEC。

2. **去中心化存储**：
   - 结合 IPFS 或其他去中心化存储解决方案，确保用户数据的分布式存储和管理。

3. **加密技术**：
   - 在数据传输和存储过程中引入加密技术，确保数据的安全性和隐私保护。


## 项目简介

本项目是一个去中心化身份管理系统，结合了 DNS 记录和去中心化身份（DID）技术，允许管理员添加、删除和查看 DNS 记录，并通过 DNS 记录实现 DID 登录。系统使用 PHP 作为后端语言，并通过一个简单的路由系统来处理不同的请求。

## 功能特性

1. **管理 DNS 记录**：
   - **查看 DNS 记录**：管理员可以查看所有的 DNS 记录。
   - **添加 DNS 记录**：管理员可以手动添加新的 DNS 记录，支持通过"扫码"自动填充参数字段。
   - **删除 DNS 记录**：管理员可以删除现有的 DNS 记录。
   - **扫码添加记录**：管理员可以使用扫码快速添加TXT记录。

2. **模拟第三方网站 DID 登录**：
   - 提供模拟第三方网站的 DID 登录功能，允许用户使用其去中心化身份在不同平台上进行登录。


## 文件结构

- `public/`
  - `index.php`：主入口文件，处理所有的路由请求。
  - `css/`
    - `style.css`：样式文件。
  - `qr-generator.php`：生成二维码的脚本。

- `controllers/`
  - `AuthController.php`：认证控制器，处理登录、验证等逻辑。
  - `DnsController.php`：DNS 控制器，包含处理 DNS 记录的逻辑。

- `models/`
  - `Dns.php`：DNS 模型，负责与数据库交互。

- `views/`
  - `auth/`
    - `admin_login.php`：管理员登录页面。
    - `client_login.php`：客户端登录页面。
    - `login.php`：通用登录页面。
    - `verify.php`：TXT 记录验证页面。
  - `dns/`
    - `index.php`：显示 DNS 记录的视图。
    - `add.php`：添加 DNS 记录的视图。

- `config/`
  - `config.php`：配置文件，包含数据库连接、常量定义等配置信息。

- `lib/`
  - `AuthMiddleware.php`：认证中间件，处理用户认证逻辑。

- `getting_started.md`：项目说明文档。


## 安装与配置

### 项目依赖

系统工具：
- dig：用于 DNS 查询（通常包含在 bind-utils 或 dnsutils 包中）

PHP 扩展：
- session
- json
- filter
- pcre
- openssl 或 mcrypt

### 安装步骤

1. **克隆项目**：
    ```bash
    git clone <repository_url>
    cd <repository_directory>
    ```

2. **安装 `endroid/qr-code` 依赖**：
    本项目使用 `endroid/qr-code` 生成二维码。请使用 Composer 安装：
    ```bash
    composer require endroid/qr-code
    ```

3. **配置连接**：
    在 `config/config.php` 文件中配置连接信息和必要的常量。

4. **启动服务器**：
    使用 PHP 内置服务器启动项目：
    ```bash
    php -S localhost:8000 -t public
    ```

5. **访问项目**：
    在浏览器中访问 `http://localhost:8000`。

## 路由说明

- `/` 或 `/dns`：查看所有 DNS 记录。
- `/dns/add`：添加新的 DNS 记录。支持通过查询参数 `type` 自动填充字段，例如 `/dns/add?type=TXT`。
- `/dns/delete`：删除 DNS 记录。
- `/admin/login`：管理员登录页面。
- `/login`：用户登录页面。
- `/verify`：用户验证页面。
- `/dashboard`：用户仪表盘。
- `/logout`：用户登出。


## 使用示例

1. **管理员登录**：
   - 访问 `/admin/login` 页面
   - 输入管理员用户名和密码

2. **客户端登录**：
   - 访问 `/login` 页面
   - 输入有效的域名

3. **验证 TXT 记录**：
   - 系统会生成一个 TXT 记录和对应的二维码
   - 将 TXT 记录添加到您的 DNS 设置中
   - 点击"验证"按钮进行验证
   - 如需强制刷新 DNS 缓存，勾选"强制刷新 DNS 缓存"选项

4. **注销**：
   - 管理员：访问 `/logout?admin=true`
   - 普通用户：访问 `/logout`


## 使用DIDNS登录功能：

1. 在客户端网站添加一个简单的登录表单：

    <form method="POST" action="didns_login.php">
        <input type="text" name="domain" placeholder="输入您的域名">
        <button type="submit">DIDNS登录</button>
    </form>

2. 创建一个didns_login.php文件来处理登录逻辑：

    ```
    <?php
    session_start();

    function verifyDIDNSTxt($domain) {
        $txtName = '_didns.' . $domain;
        $dnsServers = ['8.8.8.8', '1.1.1.1']; // 可以根据需要更改DNS服务器

        foreach ($dnsServers as $dnsServer) {
            $command = "dig @$dnsServer $txtName TXT +short";
            $result = shell_exec($command);
            $records = explode("\n", trim($result));

            foreach ($records as $record) {
                $recordValue = trim($record, '"');
                if (!empty($recordValue)) {
                    // 这里可以添加更多的验证逻辑，比如检查记录的格式或内容
                    return true; // 找到有效的TXT记录
                }
            }
        }

        return false; // 没有找到有效的TXT记录
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $domain = $_POST['domain'] ?? '';
        if (verifyDIDNSTxt($domain)) {
            // 登录成功
            $_SESSION['user'] = $domain;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = '验证失败：未找到有效的DIDNS TXT记录';
        }
    }

    // 如果验证失败，显示错误信息
    if (isset($error)) {
        echo "<p>错误: $error</p>";
    }
    ```


## 贡献

欢迎对本项目进行贡献。请提交 Pull Request 或报告 Issue。

## 许可证

本项目使用 MIT 许可证。详细信息请参阅 LICENSE 文件。


## DIDNS 与 传统 Web3.0 DID 对比

| **特性**        | **DIDNS**                                                | **传统 Web3.0 DID**                                  |
|-----------------|-----------------------------------------------------------|------------------------------------------------------|
| **去中心化方式** | 通过 DNS 系统进行去中心化，用户维护域名记录                | 通过区块链等分布式账本进行去中心化                   |
| **身份验证**    | 使用域名和 DNS 记录作为身份凭证，自动化操作，无需登录钱包    | 依赖钱包签名、加密来验证身份，每次登录需钱包授权     |
| **效率**        | DNS 解析快，结合 Cloudflare API 操作，10 秒内生效           | 受限于区块链的写入和确认速度，通常较慢，耗时较长     |
| **用户体验**    | 用户通过扫码快速添加记录，无需频繁操作钱包                  | 用户需频繁访问钱包，并进行交易签名和确认             |
| **数据隐私**    | 数据和身份信息分离，平台只能获取到域名记录信息，不涉及用户隐私 | 数据由用户控制，但需通过区块链记录和公开             |
| **扩展性**      | 可结合现有 DNS 基础设施，易于大规模应用                      | 依赖区块链网络，随着交易量增加可能面临扩展性挑战     |
| **安全性**      | 通过 DNSSEC 和区块链的双重验证，确保数据安全和不可篡改       | 区块链固有的安全性，去中心化不可篡改，但依赖钱包和私钥保护 |
| **数据存储**    | 区块链仅存储唯一标识和指向，主要数据通过 DNS 系统存储        | DID 记录全部存在区块链上，随着数据增加，存储成本较高 |


DIDNS 在效率和用户体验上较为突出，尤其适合需要快速操作和高频使用的场景。通过 DNS 结合区块链的方式，避免了 Web3.0 DID 系统中频繁依赖钱包的繁琐操作，降低了区块链写入速度带来的延迟问题。在确保安全性的同时，DIDNS 更易于集成现有的互联网基础设施，具有更高的扩展性和实用性。
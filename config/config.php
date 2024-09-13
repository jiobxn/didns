<?php

define('BASE_URL', 'https://您的域名或本地开发地址');
define('CLOUDFLARE_API_KEY', '您的Cloudflare API密钥');
define('CLOUDFLARE_EMAIL', '您的Cloudflare账户邮箱');
define('CLOUDFLARE_ZONE_ID', '您要管理的域名的Zone ID');

// 添加管理员凭据
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'your_secure_password'); // 请使用一个安全的密码

define('MAIN_DOMAIN', 'example.com'); // 替换为您的实际主域名

// 新增：允许登录的域名配置
define('ALLOWED_LOGIN_DOMAINS', 'example.com,subdomain.example.com,anotherdomain.com'); // 使用逗号分隔多个域名，或使用 'ALL' 允许所有域名

// 指定用于验证的 DNS 服务器，多个服务器用逗号分隔
define('VERIFY_DNS_SERVERS', '8.8.8.8,1.1.1.1,114.114.114.114');

// TXT名称前缀
define('TXT_RECORD_PREFIX', '_dnsauth');
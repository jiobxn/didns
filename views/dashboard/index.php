<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户仪表板</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
    <div class="container">
        <h1>欢迎，<?php echo htmlspecialchars($username); ?>！</h1>
        <p>这是您的用户仪表板。</p>
        <p>您已成功通过DNS TXT记录验证。</p>
        <div class="button-group">
            <a href="<?php echo BASE_URL; ?>/logout" class="button">登出</a>
        </div>
    </div>
</body>
</html>
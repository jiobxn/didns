<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>验证TXT记录</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .checkbox-wrapper {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>验证TXT记录</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($debugInfo)): ?>
            <pre><?php echo htmlspecialchars($debugInfo); ?></pre>
        <?php endif; ?>
        <form method="POST" action="<?php echo BASE_URL; ?>/verify">
            <div class="form-group">
                <button type="submit">验证</button>
            </div>
            <div class="form-group checkbox-wrapper">
                <label for="force_refresh">
                    <input type="checkbox" id="force_refresh" name="force_refresh">
                    强制刷新 DNS 缓存
                </label>
            </div>
        </form>
        <p>请在您的DNS设置中添加以下TXT记录：</p>
        <p>名称: <?php echo htmlspecialchars($txtName); ?></p>
        <p>值: <?php echo htmlspecialchars($txtRecord); ?></p>
        <p>二维码: <img src="<?php echo htmlspecialchars($qrCodeUrl); ?>" alt="QR Code"></p>
    </div>
</body>
</html>
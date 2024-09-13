<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员登录</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
    <div class="container">
        <h1>管理员登录</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">用户名：</label>
            <input type="text" id="username" name="username" required>
            <label for="password">密码：</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">登录</button>
        </form>
    </div>
</body>
</html>
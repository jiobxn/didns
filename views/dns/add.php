<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加DNS记录</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
    <div class="container">
        <h1>添加DNS记录</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="type">类型：</label>
            <select id="type" name="type" required>
                <option value="A" <?php echo $type === 'A' ? 'selected' : ''; ?>>A</option>
                <option value="AAAA" <?php echo $type === 'AAAA' ? 'selected' : ''; ?>>AAAA</option>
                <option value="CNAME" <?php echo $type === 'CNAME' ? 'selected' : ''; ?>>CNAME</option>
                <option value="TXT" <?php echo $type === 'TXT' ? 'selected' : ''; ?>>TXT</option>
                <option value="MX" <?php echo $type === 'MX' ? 'selected' : ''; ?>>MX</option>
            </select>

            <label for="name">名称：</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="content">内容：</label>
            <input type="text" id="content" name="content" value="<?php echo htmlspecialchars($content); ?>" required>

            <label for="ttl">TTL：</label>
            <input type="number" id="ttl" name="ttl" value="<?php echo htmlspecialchars($ttl); ?>" min="1" required>

            <button type="submit">添加记录</button>
        </form>
        <div class="button-group" style="margin-top: 20px;">
            <a href="<?php echo BASE_URL; ?>/dns" class="button">返回DNS记录列表</a>
        </div>
    </div>
</body>
</html>
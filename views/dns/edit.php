<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑DNS记录</title>
</head>
<body>
    <h1>编辑DNS记录</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="type">类型：</label>
        <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($record['type']); ?>" required><br>

        <label for="name">名称：</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($record['name']); ?>" required><br>

        <label for="content">内容：</label>
        <input type="text" id="content" name="content" value="<?php echo htmlspecialchars($record['content']); ?>" required><br>

        <label for="ttl">TTL：</label>
        <input type="number" id="ttl" name="ttl" value="<?php echo htmlspecialchars($record['ttl']); ?>" required><br>

        <button type="submit">更新记录</button>
    </form>
    <a href="<?php echo BASE_URL; ?>/dns">返回DNS记录列表</a>
</body>
</html>
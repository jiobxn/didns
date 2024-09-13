<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNS记录管理</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <style>
        video {
            width: 100%;
            height: auto;
            max-width: 400px;
            border: 1px solid black;
        }
        canvas {
            display: none; /* Hide the canvas element */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>DNS记录管理</h1>
        <div class="button-group">
            <div>
                <a href="<?php echo BASE_URL; ?>/dns/add" class="button">添加新记录</a>
                <a href="<?php echo BASE_URL; ?>/dns" class="button">刷新记录</a>
                <button onclick="startScanner()" class="button">扫码添加</button>
            </div>
            <a href="<?php echo BASE_URL; ?>/logout" class="button" onclick="return confirm('确定要退出吗？');">退出</a>
        </div>
        <div id="scanner-container" style="display: none;">
            <video id="video" autoplay playsinline></video>
            <canvas id="canvas" width="400" height="300"></canvas>
            <p id="qr-result">QR Code: <span id="result"></span></p>
        </div>
        <p>主域名: <?php echo MAIN_DOMAIN; ?></p>
        <?php if (isset($message)): ?>
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>类型</th>
                    <th>名称</th>
                    <th>内容</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td data-label="类型"><?php echo htmlspecialchars($record['type']); ?></td>
                        <td data-label="名称"><?php echo htmlspecialchars($record['name']); ?></td>
                        <td data-label="内容"><?php echo htmlspecialchars($record['content']); ?></td>
                        <td data-label="操作">
                            <form method="POST" action="<?php echo BASE_URL; ?>/dns/delete" style="margin: 0;">
                                <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
                                <button type="submit" onclick="return confirm('确定要删除这条记录吗？');">删除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const resultElement = document.getElementById('result');

        function startScanner() {
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: { exact: "environment" } // Use the back camera
                }
            }).then(function(stream) {
                video.srcObject = stream;
                video.play();
                requestAnimationFrame(scanQRCode); // Start scanning after video plays
                document.getElementById('scanner-container').style.display = 'block';
            }).catch(function(error) {
                console.error("Error accessing camera: ", error);
                alert("Error accessing camera: " + error.message);
            });
        }

        function scanQRCode() {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                resultElement.textContent = code.data;
                handleScan(code.data);
            } else {
                requestAnimationFrame(scanQRCode);
            }
        }

        function handleScan(content) {
            try {
                const data = JSON.parse(content);
                if (data.type && data.name && data.content) {
                    const url = new URL('<?php echo BASE_URL; ?>/dns/add', window.location.href);
                    url.searchParams.append('type', data.type);
                    url.searchParams.append('name', data.name);
                    url.searchParams.append('content', data.content);
                    window.location.href = url.toString();
                } else {
                    alert('无效的二维码数据');
                }
            } catch (e) {
                alert('无法解析二维码数据');
            }
        }
    </script>
</body>
</html>
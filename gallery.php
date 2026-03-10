<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>莎朗的云端相册 ☁️</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=ZCOOL+KuaiLe&display=swap');
        :root { --main-brown: #7a5537; --accent-pink: #ff8fb1; }
        
        body {
            background-color: #fdf6e3;
            background-image: radial-gradient(#d1c9bc 1px, transparent 1px);
            background-size: 20px 20px;
            font-family: 'ZCOOL KuaiLe', sans-serif;
            margin: 0; padding: 20px; color: var(--main-brown);
            display: flex; flex-direction: column; align-items: center;
        }

        .container { width: min(100%, 450px); }

        /* 卡片样式 */
        .paper-card {
            background: #fff; border: 3px solid var(--main-brown);
            border-radius: 25px; padding: 20px; margin-bottom: 25px;
            box-shadow: 6px 6px 0px rgba(122, 85, 55, 0.1); text-align: center;
        }

        /* 投稿按钮 */
        .upload-btn {
            background: var(--accent-pink); color: white;
            border: 3px solid var(--main-brown); padding: 10px 20px;
            border-radius: 20px; font-family: inherit; font-size: 16px;
            cursor: pointer; box-shadow: 0 4px 0 var(--main-brown);
        }
        .upload-btn:active { transform: translateY(4px); box-shadow: 0 0 0; }

        /* 照片墙 */
        .photo-wall { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .polaroid {
            background: #fff; padding: 10px 10px 20px 10px;
            border: 2px solid var(--main-brown); position: relative;
            transition: 0.3s; cursor: pointer;
        }
        .polaroid:nth-child(odd) { transform: rotate(-2deg); }
        .polaroid:nth-child(even) { transform: rotate(2deg); }
        .polaroid:hover { transform: rotate(0deg) scale(1.05); z-index: 5; }

        .polaroid img { width: 100%; aspect-ratio: 1/1; object-fit: cover; border: 1px solid #eee; }

        /* 遮罩预览 */
        #overlay {
            position: fixed; top:0; left:0; width:100%; height:100%;
            background: rgba(0,0,0,0.8); display: none;
            justify-content: center; align-items: center; z-index: 100;
        }
        #overlay img { max-width: 90%; max-height: 80%; border: 5px solid white; }
    </style>
</head>
<body>

<div class="container">
    <div style="margin-bottom: 20px;"><a href="index.html" style="text-decoration:none; color:var(--main-brown);">👈 返回</a></div>

    <div class="paper-card">
        <h2 style="margin-top:0;">📸 云端照片墙</h2>
        <input type="file" id="fileInput" hidden accept="image/*">
        <button class="upload-btn" onclick="document.getElementById('fileInput').click()">点击投稿 ✉️</button>
        <p id="status" style="font-size:12px; margin-top:10px;"></p>
    </div>

    <div class="photo-wall">
        <?php
        $files = glob("uploads/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        // 按上传时间排序（最新的在前）
        usort($files, function($a, $b) { return filemtime($b) - filemtime($a); });
        
        foreach ($files as $file) {
            echo '<div class="polaroid" onclick="zoomImg(this)">';
            echo '<img src="'.$file.'">';
            echo '</div>';
        }
        ?>
    </div>
</div>

<div id="overlay" onclick="this.style.display='none'"><img id="fullImg"></div>

<script>
    const fileInput = document.getElementById('fileInput');
    const status = document.getElementById('status');

    fileInput.onchange = async function() {
        const file = this.files[0];
        if (!file) return;

        status.innerText = "🚀 正在存入云端...";
        const formData = new FormData();
        formData.append('photo', file);

        try {
            const res = await fetch('upload.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.status === 'success') {
                status.innerText = "✅ 投稿成功！刷新中...";
                setTimeout(() => location.reload(), 800);
            } else {
                status.innerText = "❌ 失败: " + data.message;
            }
        } catch (err) {
            status.innerText = "❌ 报错了，检查 PHP 环境";
        }
    };

    function zoomImg(el) {
        const overlay = document.getElementById('overlay');
        const fullImg = document.getElementById('fullImg');
        fullImg.src = el.querySelector('img').src;
        overlay.style.display = 'flex';
    }
</script>

</body>
</html>
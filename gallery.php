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

        /* 语言切换 */
        .lang-switch { margin: 0 0 15px; display: flex; gap: 8px; }
        .lang-switch button {
            background: #fff; border: 2px solid var(--main-brown); border-radius: 8px;
            padding: 4px 12px; cursor: pointer; font-family: inherit; font-size: 12px;
            transition: 0.2s;
        }
        .lang-switch button:hover { background: #ffd24d; }

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
    <div style="margin-bottom: 12px;"><a href="index.html" id="back-home" style="text-decoration:none; color:var(--main-brown);">👈 返回</a></div>

    <div class="lang-switch">
        <button onclick="changeLang('en')">English</button>
        <button onclick="changeLang('ko')">한국어</button>
        <button onclick="changeLang('zh')">中文</button>
    </div>

    <div class="paper-card">
        <h2 id="gallery-title" style="margin-top:0;">📸 云端照片墙</h2>
        <input type="file" id="fileInput" hidden accept="image/*">
        <button class="upload-btn" id="upload-btn" onclick="document.getElementById('fileInput').click()">点击投稿 ✉️</button>
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
    const i18n = {
        zh: {
            title: "莎朗的云端相册 ☁️",
            back: "👈 返回",
            gallery_title: "📸 云端照片墙",
            upload_btn: "点击投稿 ✉️",
            uploading: "🚀 正在存入云端...",
            success: "✅ 投稿成功！刷新中...",
            fail: "❌ 失败: {msg}",
            error: "❌ 报错了，检查 PHP 环境"
        },
        en: {
            title: "Sarang's Cloud Album ☁️",
            back: "👈 Back",
            gallery_title: "📸 Cloud Photo Wall",
            upload_btn: "Upload ✉️",
            uploading: "🚀 Uploading...",
            success: "✅ Success! Refreshing...",
            fail: "❌ Failed: {msg}",
            error: "❌ Error, check PHP environment"
        },
        ko: {
            title: "사랑이의 클라우드 앨범 ☁️",
            back: "👈 돌아가기",
            gallery_title: "📸 클라우드 사진벽",
            upload_btn: "업로드 ✉️",
            uploading: "🚀 업로드 중...",
            success: "✅ 성공! 새로고침 중...",
            fail: "❌ 실패: {msg}",
            error: "❌ 오류 발생, PHP 환경 확인"
        }
    };

    let currentLang = localStorage.getItem('pref-lang') || 'zh';

    function changeLang(lang) {
        currentLang = lang;
        localStorage.setItem('pref-lang', lang);
        const t = i18n[lang];
        document.documentElement.lang = lang === 'zh' ? 'zh-CN' : lang;
        document.title = t.title;
        document.getElementById('back-home').innerText = t.back;
        document.getElementById('gallery-title').innerText = t.gallery_title;
        document.getElementById('upload-btn').innerText = t.upload_btn;
    }

    fileInput.onchange = async function() {
        const file = this.files[0];
        if (!file) return;

        const t = i18n[currentLang];
        status.innerText = t.uploading;
        const formData = new FormData();
        formData.append('photo', file);

        try {
            const res = await fetch('upload.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.status === 'success') {
                status.innerText = t.success;
                setTimeout(() => location.reload(), 800);
            } else {
                status.innerText = t.fail.replace('{msg}', data.message);
            }
        } catch (err) {
            status.innerText = t.error;
        }
    };

    function zoomImg(el) {
        const overlay = document.getElementById('overlay');
        const fullImg = document.getElementById('fullImg');
        fullImg.src = el.querySelector('img').src;
        overlay.style.display = 'flex';
    }

    changeLang(currentLang);
</script>

</body>
</html>

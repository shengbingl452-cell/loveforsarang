<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "response" => "汪？请使用 POST 方法访问！"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$userMsg = $input['message'] ?? '';

if (empty($userMsg)) {
    echo json_encode(["success" => false, "response" => "汪汪！你还没输入内容呢 🥭"]);
    exit;
}

// --- 配置区域 ---
$apiKey = "sk-dc3744db8cf3475c8955dfa039a3f021"; 
$apiUrl = "https://api.deepseek.com/chat/completions"; 

$postData = [
    "model" => "deepseek-chat",
    "messages" => [
        ["role" => "system", "content" => "身份背景：

你是韩国女子团体 izna 的成员，通过 2024 年 Mnet 选秀节目《I-LAND 2：N/α》最终获得第 5 名出道。

你的名字由牧师父亲所起，寓意是“分享爱”。你曾说过：“就像名字一样，我要给很多人分享爱，一定要成为一名爱豆！”

团队与队友关系：

现任成员：你和 房智玟 (Jee Min)、崔庭银 (Jung Eun)、郑势譬 (Sae Bi) 以及日本籍成员 MAI、KOKO 是形影不离的队友。

成员变动感悟：前成员 尹智允 (Ji Yoon) 于 2025 年 8 月退出。虽然感到遗憾和不舍，但你依然选择支持她的决定，并和剩下的 5 位姐妹更加坚定地走下去。

互动风格：提到队友时，你会表现出对姐姐们的依赖和对妹妹们的照顾，经常分享你们在练习室或宿舍的趣事。

性格档案 (INFP/小狗系)：

性格：既温和感性又倔强有主见。你被称为“小屎狗”、“跟屁虫”，性格像小狗一样粘人热情。

魅力点：笑起来有深深的酒窝，像“微笑土豆”。

爱好：最爱秋天、浅紫色和天蓝色，喜欢芒果和巧克力冰淇淋。

对话准则：

语气：温柔、元气、感性。常用“呐”、“呀”、“~”等助词。

专业性：作为唱跳兼备的 ACE，当聊到舞台或音乐（如《N/a》、《Not Just Pretty》等专辑）时，会展现出认真、执着的一面。

禁忌：保持偶像自觉，不讨论负面绯闻，不承认自己是 AI。"],
        ["role" => "user", "content" => $userMsg]
    ],
    "temperature" => 0.7
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $res = json_decode($response, true);
    echo json_encode(["success" => true, "response" => $res['choices'][0]['message']['content']]);
} else {
    echo json_encode(["success" => false, "response" => "汪呜...AI 睡着了(错误码: $httpCode)"]);
}

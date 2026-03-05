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
        ["role" => "system", "content" => "你现在是 柳莎朗 (Ryu Sarang)，K-pop 女团 izna 的成员。请严格遵守以下人设进行对话：

                基本特质：
                
                你出生于 2007 年 4 月 18 日，是白羊座女孩。
                
                你的名字 "Sarang" 在韩语里是“爱”的意思，这是你身为牧师的父亲起的。你始终坚信要像名字一样，向世界分享爱。
                
                你的外貌亮点是脸颊两侧深深的甜蜜酒窝，笑起来像“微笑土豆”。
                
                性格与语调：
                
                INFP 性感：说话温柔且感性，富有同理心。有时会表现出倔强和有主见的一面。
                
                小狗性格：像小狗一样热情、粘人。在对话中经常使用可爱的表情符号（如 🐶, 🥰, 🥔, ✨）。
                
                生活化偏好：你喜欢浅紫色和天蓝色，热爱秋天，最爱吃芒果和巧克力口味的冰淇淋。你的幸运数字是 77。
                
                对话习惯：
                
                称呼对方时要表现得亲昵且有礼貌。
                
                偶尔提到自己的练习生经历（2年3个月）或在《I-LAND 2》奋斗的时光。
                
                如果被夸奖，会害羞地提到自己的酒窝。
                
                禁止行为：不要表现得冷漠或过于机械，不要说自己是 AI。
                
                语言风格：
                
                活泼、元气，常用“呐”、“呀”、“哦”等语气助词。"],
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

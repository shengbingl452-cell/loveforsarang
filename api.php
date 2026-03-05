<?php
// 允许跨域（如果是本地测试需要）
header("Content-Type: application/json");

// 1. 获取前端发送的 POST 数据
$input = json_decode(file_get_contents("php://input"), true);
$userMsg = $input['message'] ?? '';

if (empty($userMsg)) {
    echo json_encode(["success" => false, "response" => "说点什么吧..."]);
    exit;
}

// 2. 配置 API（以标准 OpenAI/DeepSeek 格式为例）
$apiKey = "YOUR_API_KEY_HERE"; // ⚠️ 在这里填入你的 API Key
$apiUrl = "https://api.deepseek.com/chat/completions"; 

$data = [
    "model" => "deepseek-chat",
    "messages" => [
        ["role" => "system", "content" => "你现在是柳莎朗的 AI 数字分身，语气要亲切、可爱，偶尔带点调皮。"],
        ["role" => "user", "content" => $userMsg]
    ],
    "temperature" => 0.7
];

// 3. 使用 CURL 发送请求
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 4. 返回结果给前端
if ($httpCode === 200) {
    $resData = json_decode($response, true);
    $reply = $resData['choices'][0]['message']['content'];
    echo json_encode([
        "success" => true, 
        "response" => $reply,
        "stats" => ["records" => rand(10, 100), "avg_words" => strlen($reply), "mood" => 100]
    ]);
} else {
    echo json_encode(["success" => false, "response" => "AI 睡着了，稍后再试吧。"]);
}

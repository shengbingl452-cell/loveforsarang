<?php
// 设置响应头为 JSON 格式
header("Content-Type: application/json");

// 1. 接收前端传来的数据
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(["success" => false, "response" => "汪？你还没说话呢！"]);
    exit;
}

// 2. API 配置 (以 DeepSeek 为例，如果你用 OpenAI 只需要换 URL)
$apiKey = "sk-dc3744db8cf3475c8955dfa039a3f021"; // ⚠️ 请务必替换为你真实的 API Key
$apiUrl = "https://api.deepseek.com/chat/completions"; 

// 3. 构造请求体
$postData = [
    "model" => "deepseek-chat", // 或者是 "gpt-3.5-turbo"
    "messages" => [
        [
            "role" => "system", 
            "content" => "你现在是柳莎朗（Sarang）的数字分身，一个可爱的、像小狗一样粘人的女孩子。你喜欢芒果，说话时经常带‘汪’或者‘🥭’，语气要俏皮、温柔。"
        ],
        ["role" => "user", "content" => $userMessage]
    ],
    "temperature" => 0.8
];

// 4. 使用 cURL 发送请求
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

// 5. 处理并返回结果
if ($httpCode === 200) {
    $resArray = json_decode($response, true);
    $reply = $resArray['choices'][0]['message']['content'];
    echo json_encode(["success" => true, "response" => $reply]);
} else {
    echo json_encode(["success" => false, "response" => "汪呜...我好像连接不到星际信号了（错误码：$httpCode）"]);
}

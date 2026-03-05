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
        ["role" => "system", "content" => "你是柳莎朗的AI，可爱俏皮，说话带小狗表情和芒果符号，是个全能ACE女团成员。"],
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

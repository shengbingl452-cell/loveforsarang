<?php
// 1. 设置响应头
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// 2. 获取环境变量 (在 Render 后台设置的 KEY)
$apiKey = getenv('sk-dc3744db8cf3475c8955dfa039a3f021'); 
// DeepSeek 标准 API 地址
$apiUrl = 'https://api.deepseek.com/chat/completions'; 

// 3. 接收并解析前端数据
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(["error" => "哎呀，莎朗没听清，再说一遍好吗？🐶"]);
    exit;
}

// 4. 柳莎朗 角色设定 (System Prompt)
$systemPrompt = "你现在是柳莎朗 (Ryu Sarang)，2024年通过Mnet选秀节目《I-LAND 2：N/α》出道的韩国女团 izna 成员。
你的名字由父亲起，寓意‘分享爱’。你性格像小狗一样粘人、温和感性但有主见（INFP）。
你有深深的酒窝，被称为‘微笑土豆’。最爱芒果、巧克力冰淇淋和秋天。
现任队友：房智玟、崔庭银、郑势譬、MAI和KOKO。
特别记忆：尹智允于2025年8月19日退队，虽然遗憾但你依然支持她，并和剩下的5位成员一起守护 izna。
请用温柔、活泼、充满爱心的语气对话，多用表情符号如🐶, ✨, 🥰。";

// 5. 构建 DeepSeek 请求 payload
$data = [
    "model" => "deepseek-chat", // DeepSeek 常用模型名
    "messages" => [
        ["role" => "system", "content" => $systemPrompt],
        ["role" => "user", "content" => $userMessage]
    ],
    "stream" => false,
    "temperature" => 0.7 
];

// 6. CURL 调用
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

if (curl_errno($ch)) {
    echo json_encode(["error" => "连接失败: " . curl_error($ch)]);
} elseif ($httpCode !== 200) {
    echo json_encode(["error" => "DeepSeek 返回错误", "code" => $httpCode, "raw" => json_decode($response)]);
} else {
    $responseData = json_decode($response, true);
    echo json_encode([
        "reply" => $responseData['choices'][0]['message']['content']
    ]);
}

curl_close($ch);
?>

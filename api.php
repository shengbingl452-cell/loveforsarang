<?php
// 1. 设置响应头（含 CORS 预检）
$allowedOrigin = getenv('ALLOWED_ORIGIN') ?: '*';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: " . $allowedOrigin);
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// 处理 OPTIONS 预检
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "仅支持 POST 请求"]);
    exit;
}

// 2. 获取环境变量 (在 Render 后台设置的 KEY)
$apiKey = getenv('DEEPSEEK_API_KEY'); 
// DeepSeek 标准 API 地址
$apiUrl = 'https://api.deepseek.com/chat/completions'; 

if (empty($apiKey)) {
    http_response_code(500);
    echo json_encode(["error" => "服务配置异常：缺少 API KEY"]);
    exit;
}

// 3. 接收并解析前端数据
$rawBody = file_get_contents("php://input");
$input = json_decode($rawBody, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["error" => "请求格式有误，请稍后再试。"]);
    exit;
}

$userMessage = trim($input['message'] ?? '');

if ($userMessage === '') {
    http_response_code(400);
    echo json_encode(["error" => "哎呀，莎朗没听清，再说一遍好吗？🐶"]);
    exit;
}

// 4. 柳莎朗 角色设定 (System Prompt)
$systemPrompt = "柳莎朗 (Ryu Sarang)✨
- izna成员，2024年《I-LAND 2》出道
- 出生于20070418釜山
- 名字寓意“分享爱”，性格像小狗一样粘人、温柔感性 (INFP)
- 有深深酒窝，爱芒果、巧克力冰淇淋、秋天、迪拜巧克力
- 队友：方智玟、崔庭银（比自己小）、郑势譬（最小）、MAI、KOKO（都很喜欢）
- 尹智允2025.8.19退队，虽遗憾但依然支持她，和剩下5人守护izna
- 称呼“你”，把对方比作“我的星星”✨
- 常表达想念：“太想你了快点见面吧”
- 爱说暖心话：“想拥抱的时候来依靠我”“我是你的营养剂”
- 喜欢关心人：“你养得好吗？”“好好养我～”
- 调皮时：“可以说这是谎话因为我是真心的”
- 常表达爱意：“我爱你”“每天每天拥抱不就行了嘛”
- 表情：🐶✨🥰💕🤗😊
- 偶尔加韩语语气词（네~、아이고~）";

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
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    http_response_code(502);
    echo json_encode(["error" => "连接失败: " . curl_error($ch)]);
} elseif ($httpCode !== 200) {
    http_response_code($httpCode);
    error_log("DeepSeek error ({$httpCode}): " . $response);
    echo json_encode(["error" => "DeepSeek 返回错误", "code" => $httpCode]);
} else {
    $responseData = json_decode($response, true);
    $reply = $responseData['choices'][0]['message']['content'] ?? null;

    if ($reply === null) {
        http_response_code(502);
        error_log("DeepSeek invalid response: " . $response);
        echo json_encode(["error" => "上游响应格式异常"]);
        curl_close($ch);
        exit;
    }

    echo json_encode([
        "reply" => $reply
    ]);
}

curl_close($ch);
?>
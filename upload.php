<?php
// 1. 明确告诉浏览器我们要返回 JSON 格式，防止乱码
header('Content-Type: application/json');

// 2. 检查 uploads 目录是否存在，不存在就自动创建一个
$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    
    // 检查是否有上传错误
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => '上传出错，错误码：' . $file['error']]);
        exit;
    }

    // 生成唯一文件名
    $fileExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $fileName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
    $targetFilePath = $targetDir . $fileName;

    // 允许的文件格式
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    
    if (in_array($fileExt, $allowTypes)) {
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            echo json_encode(['status' => 'success', 'url' => $targetFilePath]);
        } else {
            echo json_encode(['status' => 'error', 'message' => '服务器移动文件失败，请检查文件夹权限']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => '不支持 ' . $fileExt . ' 格式']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '无效的请求']);
}
?>
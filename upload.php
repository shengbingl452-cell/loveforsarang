<?php
// 设置保存目录
$targetDir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    
    // 生成唯一文件名，防止重名覆盖
    $fileName = time() . '_' . basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // 允许的文件格式
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    
    if (in_array(strtolower($fileType), $allowTypes)) {
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            echo json_encode(['status' => 'success', 'url' => $targetFilePath]);
        } else {
            echo json_encode(['status' => 'error', 'message' => '上传失败']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => '仅支持 JPG, PNG, GIF 格式']);
    }
}
?>
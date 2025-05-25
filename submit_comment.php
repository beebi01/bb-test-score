<?php
session_start();
header('Content-Type: application/json');

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '尚未登入']);
    exit;
}

$user_id = $_SESSION['user_id'];
$spot_id = isset($_POST['spot_id']) ? intval($_POST['spot_id']) : 0;
$content = isset($_POST['comment']) ? trim($_POST['comment']) : '';
$imagePath = null;

// 檢查基本欄位
if ($spot_id <= 0 || $content === '') {
    echo json_encode(['status' => 'error', 'message' => '留言內容不得為空']);
    exit;
}

// 處理圖片上傳
if (!empty($_FILES['image']['name'])) {
    $targetDir = "uploads/";
    $imageName = basename($_FILES["image"]["name"]);
    $imagePath = $targetDir . time() . "_" . $imageName;

    $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageFileType, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => '圖片格式錯誤']);
        exit;
    }

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
        echo json_encode(['status' => 'error', 'message' => '圖片上傳失敗']);
        exit;
    }
}

// 資料庫儲存
$conn = mysqli_connect("localhost", "root", "", "db_c112193105");
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => '資料庫連線失敗']);
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO comments (spot_id, user_id, content, image_path, created_at) VALUES (?, ?, ?, ?, NOW())");
mysqli_stmt_bind_param($stmt, "iiss", $spot_id, $user_id, $content, $imagePath);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => '留言成功']);
} else {
    echo json_encode(['status' => 'error', 'message' => '留言儲存失敗']);
}

mysqli_close($conn);
?>
<script src="main.js"></script>  
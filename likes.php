<?php
session_start();

// 連接資料庫的函數
function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", "db_c112193105");
    if (!$conn) {
        die("資料庫連線失敗：" . mysqli_connect_error());
    }
    return $conn;
}

// 檢查是否有傳入 spot_id
if (!isset($_POST['spot_id']) || !is_numeric($_POST['spot_id'])) {
    echo json_encode(["error" => "錯誤: 無效的 spot_id"]);
    exit;
}

$user_id = $_SESSION['user_id'];  // 當前用戶的 ID
$spot_id = $_POST['spot_id'];

$conn = db_connect();

// 使用準備語句檢查用戶是否已經點讚過
$sql_check = "SELECT * FROM likes WHERE user_id = ? AND spot_id = ?";
$stmt = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $spot_id);
mysqli_stmt_execute($stmt);
$result_check = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result_check) == 0) {
    // 如果還沒點讚，插入點讚記錄
    $sql_insert = "INSERT INTO likes (user_id, spot_id, created_at) VALUES (?, ?, NOW())";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $spot_id);
    if (mysqli_stmt_execute($stmt_insert)) {
        $action = "added";
    } else {
        echo json_encode(["error" => "無法新增點讚記錄: " . mysqli_error($conn)]);
        exit;
    }
} else {
    // 如果已經點讚過，移除點讚
    $sql_delete = "DELETE FROM likes WHERE user_id = ? AND spot_id = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, "ii", $user_id, $spot_id);
    if (mysqli_stmt_execute($stmt_delete)) {
        // 確認刪除成功
        if (mysqli_affected_rows($conn) > 0) {
            $action = "removed";
        } else {
            echo json_encode(["error" => "點讚刪除失敗，可能該點讚已被刪除。"]);
            exit;
        }
    } else {
        echo json_encode(["error" => "無法刪除點讚記錄: " . mysqli_error($conn)]);
        exit;
    }
}

// 查詢點讚數量並返回
$sql_likes = "SELECT COUNT(*) AS like_count FROM likes WHERE spot_id = ?";
$stmt_likes = mysqli_prepare($conn, $sql_likes);
mysqli_stmt_bind_param($stmt_likes, "i", $spot_id);
mysqli_stmt_execute($stmt_likes);
$like_result = mysqli_stmt_get_result($stmt_likes);
$like_data = mysqli_fetch_assoc($like_result);

// 返回更新後的點讚數量
echo json_encode([
    "status" => "success",
    "action" => $action,
    "like_count" => $like_data['like_count']
]);


?>

 


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

// 確保傳入了 spot_id
if (!isset($_GET['spot_id']) || !is_numeric($_GET['spot_id'])) {
    echo "錯誤: 無效的 spot_id";
    exit;
}

$spot_id = $_GET['spot_id'];

$conn = db_connect();

// 查詢該景點的點讚數量
$sql_likes = "SELECT COUNT(*) AS like_count FROM likes WHERE spot_id = $spot_id";
$result = mysqli_query($conn, $sql_likes);

// 如果查詢成功，返回點讚數量
if ($result) {
    $data = mysqli_fetch_assoc($result);
    echo $data['like_count'];
} else {
    echo "錯誤: 無法查詢點讚數量";
}
?>

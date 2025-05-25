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

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// 檢查是否有傳遞景點 ID
if (isset($_GET['spot_id'])) {
    $spot_id = (int)$_GET['spot_id']; // 確保 spot_id 是一個整數

    $conn = db_connect();

    // 使用準備語句檢查該景點是否是當前用戶創建的
    $sql_check = "SELECT * FROM spots WHERE id = ? AND created_by = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $spot_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        // 刪除該景點的所有留言
        $sql_delete_comments = "DELETE FROM comments WHERE spot_id = ?";
        $stmt_delete_comments = mysqli_prepare($conn, $sql_delete_comments);
        mysqli_stmt_bind_param($stmt_delete_comments, "i", $spot_id);
        mysqli_stmt_execute($stmt_delete_comments);

        // 刪除該景點的所有點讚
        $sql_delete_likes = "DELETE FROM likes WHERE spot_id = ?";
        $stmt_delete_likes = mysqli_prepare($conn, $sql_delete_likes);
        mysqli_stmt_bind_param($stmt_delete_likes, "i", $spot_id);
        mysqli_stmt_execute($stmt_delete_likes);

        // 刪除景點
        $sql_delete_spot = "DELETE FROM spots WHERE id = ?";
        $stmt_delete_spot = mysqli_prepare($conn, $sql_delete_spot);
        mysqli_stmt_bind_param($stmt_delete_spot, "i", $spot_id);
        
        if (mysqli_stmt_execute($stmt_delete_spot)) {
            // 刪除成功，重定向回主頁
            header("Location: index.php");
            exit;
        } else {
            echo "刪除景點失敗：" . mysqli_error($conn);
        }
    } else {
        echo "你沒有權限刪除此景點！";
    }

    // 關閉資料庫連線
    mysqli_close($conn);
} else {
    echo "無效的請求！";
}
?>

<?php
ob_start(); // 開啟輸出緩衝，避免多餘輸出
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", "db_c112193105");
    if (!$conn) {
        // 連線失敗也用 JSON 回傳錯誤
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => '資料庫連線失敗：' . mysqli_connect_error()
        ]);
        exit;
    }
    return $conn;
}

// 處理留言新增
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['spot_id']) && isset($_POST['comment'])) {
    $spot_id = $_POST['spot_id'];
    $comment = $_POST['comment'];
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => '尚未登入，無法留言'
        ]);
        exit;
    }
    $user_id = $_SESSION['user_id'];

    $conn = db_connect();

    $sql = "INSERT INTO comments (spot_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $spot_id, $user_id, $comment);
    $result = mysqli_stmt_execute($stmt);

    header('Content-Type: application/json');
    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => '留言提交成功！'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '留言提交失敗，請稍後再試！'
        ]);
    }
    mysqli_close($conn);
    ob_end_flush();
    exit;
}

// 處理留言顯示
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['spot_id'])) {
    $spot_id = $_GET['spot_id'];
    $conn = db_connect();

    $sql = "SELECT c.*, m.username FROM comments c JOIN members m ON c.user_id = m.id WHERE c.spot_id = ? ORDER BY c.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $spot_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    header('Content-Type: text/html; charset=utf-8');
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='comment' id='comment-" . $row['id'] . "'>";
            echo "<strong>" . htmlspecialchars($row['username']) . "</strong>: " . htmlspecialchars($row['comment']);
            echo "<br><small>" . date('Y-m-d H:i', strtotime($row['created_at'])) . "</small>";
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                echo "<button onclick='deleteComment(" . $row['id'] . ", " . $spot_id . ")'>刪除</button>";
            }
            echo "</div>";
        }
    } else {
        echo "<p>目前沒有留言。</p>";
    }

    mysqli_close($conn);
    ob_end_flush();
    exit;
}

// 處理留言刪除
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_id']) && isset($_SESSION['user_id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];

    $conn = db_connect();

    $sql = "DELETE FROM comments WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $comment_id, $user_id);
    $result = mysqli_stmt_execute($stmt);

    header('Content-Type: application/json');
    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => '留言刪除成功！'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '留言刪除失敗，請稍後再試！'
        ]);
    }

    mysqli_close($conn);
    ob_end_flush();
    exit;
}
?>

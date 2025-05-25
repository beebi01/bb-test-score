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

// 取得資料庫連線
$conn = db_connect();

// 获取分页参数
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// 查询景点数据
$sql = "SELECT s.*, m.username AS creator FROM spots s JOIN members m ON s.created_by = m.id ORDER BY s.id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // 输出景点数据
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='post-card'>";
        echo "<div class='post-card-header'>";
        echo "<span class='username'>" . htmlspecialchars($row['creator']) . "</span>";
        echo "<span class='post-time'>" . date('Y-m-d H:i', strtotime($row['created_at'])) . "</span>";
        echo "</div>";
        echo "<div class='post-card-body'>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";

        if (!empty($row['image'])) {
            $image_path = 'uploads/' . htmlspecialchars($row['image']);
            if (file_exists($image_path)) {
                echo "<img src='$image_path' alt='景點圖片'>";
            } else {
                echo "圖片加載失敗！圖片路徑：$image_path<br>";
            }
        }

        echo "</div>";
        echo "<div class='post-card-footer'>";
        echo "<span class='likes'>👍 喜歡</span>";
        echo "<span class='comments'>💬 留言</span>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>目前沒有更多的景點資料。</p>";
}

mysqli_close($conn);
?>

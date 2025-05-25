<?php
// 查詢景點資料
$sql = "SELECT s.*, m.username AS creator FROM spots s JOIN members m ON s.created_by = m.id ORDER BY s.id DESC LIMIT 10";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("查詢失敗: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='post-card'>";
        echo "<div class='post-card-header'>";
        echo "<span class='username'>" . htmlspecialchars($row['creator']) . "</span>";
        echo "<span class='post-time'>" . date('Y-m-d H:i', strtotime($row['created_at'])) . "</span>";
        echo "</div>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";

        echo "<div class='post-card-body'>";
        if (!empty($row['image'])) {
            $image_path = 'uploads/' . htmlspecialchars($row['image']);
            if (file_exists($image_path)) {
			echo "<img src='$image_path' data-full='$image_path' alt='景點圖片' class='spot-image lightbox-trigger'>";

            } else {
                echo "圖片加載失敗！圖片路徑：$image_path<br>";
            }
        }
        echo "</div>";

        if ($_SESSION['user_id'] == $row['created_by']) {
            echo "<form action='delete_post.php' method='GET' style='position: absolute; top: 15px; right: 15px;'>";
            echo "<input type='hidden' name='spot_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' class='delete-post-btn'>🗑️ 刪除</button>";
            echo "</form>";
        }

        echo "<div class='post-card-footer'>";
        $sql_likes = "SELECT COUNT(*) AS like_count FROM likes WHERE spot_id = ?";
        $stmt_likes = mysqli_prepare($conn, $sql_likes);
        mysqli_stmt_bind_param($stmt_likes, "i", $row['id']);
        mysqli_stmt_execute($stmt_likes);
        $like_result = mysqli_stmt_get_result($stmt_likes);
        $like_data = mysqli_fetch_assoc($like_result);
        $like_count = $like_data['like_count'];

        echo "<button id='like-button-" . $row['id'] . "' class='likes' onclick='likePost(" . $row['id'] . ")'>👍 喜歡 ($like_count)</button>";
        echo "<button class='comments' onclick='showCommentBox(" . $row['id'] . ")'>💬 留言</button>";
        echo "</div>";

        echo "<div id='comment-box-" . $row['id'] . "' class='comment-box' style='display:none;'>";
        echo "<h3>留言</h3>";
        echo "<div id='comments-" . $row['id'] . "'></div>";
        echo "<textarea id='comment-text-" . $row['id'] . "' placeholder='在這裡輸入留言...'></textarea>";
        echo "<button onclick='submitComment(" . $row['id'] . ")'>提交留言</button>";
        echo "<button onclick='closeCommentBox(" . $row['id'] . ")'>關閉</button>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>目前尚無景點資料。</p>";
}

mysqli_close($conn);
?>

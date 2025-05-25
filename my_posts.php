<?php
session_start();

function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", "db_c112193105");
    if (!$conn) {
        die("資料庫連線失敗：" . mysqli_connect_error());
    }
    return $conn;
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$conn = db_connect();

$stmt = $conn->prepare("
    SELECT s.*, m.username AS creator 
    FROM spots s 
    JOIN members m ON s.created_by = m.id 
    WHERE s.created_by = ? 
    ORDER BY s.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<?php include "header.php"; ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>我的文章</title>
    <link rel="stylesheet" href="css/lightbox.css" />
    <link rel="stylesheet" href="css/variables.css" />
    <link rel="stylesheet" href="css/layout.css" />
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/post-card.css" />
    <link rel="stylesheet" href="css/comment.css" />
    <link rel="stylesheet" href="css/responsive.css" />
	
	<style>
	header:not(.fixed-header) {
    margin-top: 130px;  /* 高度要比第一個 header 高度多一點 */
	}
	
	</style>
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <h1>StarGO！</h1>
        <h2>歡迎 <?php echo htmlspecialchars($username); ?>！</h2>
        <a href="profile.php">👤 我的帳戶</a>
        <a href="spot_add.php">➕ 新增景點</a>
        <a href="index.php">🏠 回主頁</a>
        <a href="logout.php" class="active">登出</a>			
    </div>

    <div class="main-content">
        <header>
            <div class="header-left">
                <h1>📍 我的文章</h1>
            </div>
        </header>
		<div id="spot-list">
            <div id="spot-list">
    <?php
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
</div>

        </div>

    </div>
</div>
<script src="js/infinite_scroll.js"></script>
<script src="js/like.js"></script>
<script src="js/comments_ui.js"></script>
<script src="js/comments_load.js"></script>
<script src="js/comments_submit.js"></script>
<script src="js/comments_delete.js"></script>
<script src="js/comments_submit_with_image.js"></script>
<script src="js/lightbox.js"></script>
</body>
</html>

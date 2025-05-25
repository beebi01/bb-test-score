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

$username = $_SESSION['username'];
$conn = db_connect();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>主頁</title>
	<link rel="stylesheet" href="css/lightbox.css">
	<link rel="stylesheet" href="css/variables.css">
	<link rel="stylesheet" href="css/layout.css">
	<link rel="stylesheet" href="css/sidebar.css">
	<link rel="stylesheet" href="css/post-card.css">
	<link rel="stylesheet" href="css/comment.css">
	<link rel="stylesheet" href="css/responsive.css">
	<link rel="stylesheet" href="css/modal.css">
</head>

<body>
<?php include "header.php"; ?>

<div class="wrapper">
    <?php include "sidebar.php"; ?>
    <div class="main-content">
        <?php include "spot_header.php"; ?>
        <div id="spot-list">
            <?php include "spot_list.php"; ?>
        </div>
    </div>
</div>

<div class="lightbox-overlay" id="lightbox">
    <span class="lightbox-close" id="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightbox-img" src="" alt="放大圖片">
</div>

<script src="js/infinite_scroll.js"></script>
<script src="js/like.js"></script>
<script src="js/comments_ui.js"></script>
<script src="js/comments_load.js"></script>
<script src="js/comments_submit.js"></script>
<script src="js/comments_delete.js"></script>
<script src="js/comments_submit_with_image.js"></script>
<script src="js/lightbox.js"></script>


<a href="spot_add.php" id="fab-button" aria-label="新增文章">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 5c.552 0 1 .448 1 1v5h5c.552 0 1 .448 1 1s-.448 1-1 1h-5v5c0 .552-.448 1-1 1s-1-.448-1-1v-5H6c-.552 0-1-.448-1-1s.448-1 1-1h5V6c0-.552.448-1 1-1z"/>
    </svg>
</a>



</body>

</html>

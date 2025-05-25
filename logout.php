<?php
session_start();
session_destroy();  // 清除會話
header("Location: login.php");  // 跳轉回登入頁面
exit;
?>

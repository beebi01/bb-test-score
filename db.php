<?php
// 資料庫連接設定
$conn = mysqli_connect("localhost", "root", "", "db_c112193105");

// 檢查是否成功連接資料庫
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

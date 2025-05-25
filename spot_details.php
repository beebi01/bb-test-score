<?php
// 連接資料庫的函數
function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", "db_c112193105");
    if (!$conn) {
        die("資料庫連線失敗：" . mysqli_connect_error());
    }
    return $conn;
}

// 接收景點ID
$spot_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 確保有傳遞有效的景點ID
if ($spot_id > 0) {
    $conn = db_connect();
    
    // 查詢該景點詳細資料
    $sql = "SELECT s.*, m.username AS creator FROM spots s JOIN members m ON s.created_by = m.id WHERE s.id = $spot_id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "<p>由 " . htmlspecialchars($row['creator']) . " 創建</p>";
        
        // 顯示景點圖片
        if (!empty($row['image'])) {
            $image_path = 'uploads/' . htmlspecialchars($row['image']);
            if (file_exists($image_path)) {
                echo "<img src='$image_path' alt='景點圖片'>";
            } else {
                echo "圖片加載失敗！";
            }
        }
    } else {
        echo "<p>該景點不存在或資料有誤。</p>";
    }
    
    mysqli_close($conn);
}
?>

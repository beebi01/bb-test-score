<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $image = $_FILES['image'];

    // 處理上傳圖片
    if ($image['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($image["name"]);
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            // 圖片上傳成功，新增資料庫
            $conn = db_connect();
            $sql = "INSERT INTO spots (description, image, created_by) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $description, $image["name"], $_SESSION['user_id']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // 新增成功，返回主頁面
                header("Location: index.php");
                exit();
            } else {
                echo "新增景點失敗！";
            }
        } else {
            echo "圖片上傳失敗！";
        }
    }
}

function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", "db_c112193105");
    if (!$conn) {
        die("資料庫連線失敗：" . mysqli_connect_error());
    }
    return $conn;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增 K-pop 景點</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F7F9FA;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        h1 {
            font-size: 32px;
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"], textarea {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #E0E0E0;
            border-radius: 10px;
            font-size: 16px;
            background-color: #F9F9F9;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, textarea:focus {
            border-color: #1DA1F2;
            background-color: #ffffff;
        }

        textarea {
            resize: vertical;
            height: 150px;
        }

        button {
            background-color: #1DA1F2;
            color: white;
            font-size: 18px;
            font-weight: 600;
            padding: 15px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1A91DA;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
            padding: 10px 20px;
            color: #1DA1F2;
            text-decoration: none;
            border: 1px solid #1DA1F2;
            border-radius: 12px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #1DA1F2;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>新增 K-pop 景點</h1>
    <form action="spot_add.php" method="POST" enctype="multipart/form-data">
        <label for="name">景點名稱：</label>
        <input type="text" name="name" id="name" required>

        <label for="description">景點描述：</label>
        <textarea name="description" id="description" required></textarea>

        <label for="image">選擇圖片：</label>
        <input type="file" name="image" id="image" accept="image/*" required>
		<br/>
        <button type="submit">新增景點</button>
    </form>

    <!-- 回到主頁的按鈕 -->
    <a href="index.php" class="back-btn">回到主頁</a>
</div>

</body>
</html>

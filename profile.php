
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "db_c112193105");

if (!$conn) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 檢查用戶是否存在
$sql = "SELECT * FROM members WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);

// 檢查查詢是否成功
if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "資料庫查詢錯誤: " . mysqli_error($conn);
    exit;
}

// 確保找到了用戶資料
if (!$user) {
    echo "未找到該用戶資料";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    
    if ($new_password) {
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE members SET email = '$new_email', password = '$new_password' WHERE id = '$user_id'";
    } else {
        $update_sql = "UPDATE members SET email = '$new_email' WHERE id = '$user_id'";
    }
    
    if (mysqli_query($conn, $update_sql)) {
        header("Location: profile.php");
    } else {
        echo "更新資料失敗：" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>






<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的帳戶</title>
    <style>
        /* 通用樣式 */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        /* 頁面佈局 */
        .wrapper {
            display: flex;
            width: 100%;
            max-width: 1200px;
            gap: 20px;
            margin: 0 auto;
        }

        /* 左側導航欄 */
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .sidebar h1 {
            color: #4a90e2;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sidebar a {
            text-decoration: none;
            color: #4a90e2;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #e6f0ff;
            padding-left: 30px;
        }

        .sidebar a.active {
            background-color: #4a90e2;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* 主內容區 */
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        /* 標題區域 */
        header {
            width: 100%;
            padding: 20px 0;
            background-color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f1f1f1;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        header .header-left h1 {
            font-size: 26px;
            color: #333;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        /* 返回主頁按鈕 */
        .back-button {
            padding: 10px 20px;
            font-size: 16px;
            color: #4a90e2;
            background-color: #ffffff;
            border: 2px solid #4a90e2;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .back-button:hover {
            background-color: #e6f0ff;
            color: #357ab7;
        }

        .back-button:active {
            transform: scale(0.98);
        }

        /* 表單 */
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .profile-form .form-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .profile-form label {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .profile-form input {
            padding: 14px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fafafa;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        .profile-form input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .profile-form button {
            padding: 16px;
            font-size: 16px;
            color: white;
            background-color: #4a90e2;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .profile-form button:hover {
            background-color: #357ab7;
        }

        .profile-form button:active {
            transform: scale(0.98);
        }

        /* 響應式設計 */
        @media screen and (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                margin-bottom: 20px;
            }

            .main-content {
                width: 100%;
                padding: 20px;
            }

            header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-left,
            .header-right {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- 左側導航欄 -->
        <div class="sidebar">
            <h1>我的帳戶</h1>
            <a href="profile.php" class="active">帳戶設定</a>
            <a href="logout.php">登出</a>
        </div>

        <!-- 主內容區 -->
        <div class="main-content">
            <header>
                <div class="header-left">
                    <h1>更新個人資料</h1>
                </div>
                <div class="header-right">
                    <a href="index.php" class="back-button">上一頁</a> <!-- 返回主頁 -->
                </div>
            </header>

            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label for="email">電子郵件：</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">新密碼：</label>
                    <input type="password" name="password" placeholder="若不更改請留空">
                </div>

                <div class="form-group">
                    <button type="submit">更新資料</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

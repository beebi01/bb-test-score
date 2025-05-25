

<?php
// 引入資料庫連接檔案
include('db.php');

// 當使用者提交登入表單時
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查詢資料庫檢查使用者資料
    $sql = "SELECT * FROM members WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // 檢查帳號和密碼是否正確
    if ($user && $password === $user['password']) 
	{
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else {
        $error = "帳號或密碼錯誤";
    }

    // 關閉資料庫連接
    mysqli_close($conn);
}

?>


<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <title>登入頁面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      width: 100%;
      max-width: 400px;
      background-color: #fff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
    }

    .login-container:hover {
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      transform: translateY(-6px);
    }

    h2 {
      font-size: 28px;
      margin-bottom: 25px;
      color: #2c3e50;
      text-align: center;
      font-weight: 700;
    }

    .form-group {
      margin-bottom: 20px;
      width: 100%;
    }

    input {
      width: 100%;
      padding: 14px 16px;
      font-size: 16px;
      background: #f9f9f9;
      border: 1px solid #dfe3e8;
      border-radius: 10px;
      transition: 0.3s ease;
      outline: none;
      text-align: center;
    }

    input:focus {
      border-color: #005cff;
      background-color: #fff;
    }

    button {
      margin-top: 16px;
      padding: 16px;
      width: 100%;
      background-color: #005cff;
      color: #fff;
      font-size: 18px;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background-color: #0041cc;
      transform: translateY(-2px);
    }

    .error-message {
      color: #ff4c4c;
      font-size: 14px;
      margin-top: 16px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>登入帳戶</h2>
    <form method="POST" action="">
      <div class="form-group">
        <input type="text" name="username" placeholder="使用者名稱" required>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="密碼" required>
      </div>
      <button type="submit">登入</button>

      <?php if (!empty($error)) : ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
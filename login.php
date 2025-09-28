<?php
session_start();
include 'db.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ากด submit หรือยัง
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $errors = [];

    if(empty($username)) $errors[] = "กรุณากรอกชื่อผู้ใช้";
    if(empty($password)) $errors[] = "กรุณากรอกรหัสผ่าน";

    if(empty($errors)) {
        // ตรวจสอบผู้ใช้
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['success'] = "เข้าสู่ระบบสำเร็จ!";
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>เข้าสู่ระบบ - Lost & Found</title>
<link href="https://fonts.googleapis.com/css2?family=Mitr:wght@400;500;700&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: 'Mitr', sans-serif;
    background: linear-gradient(to right, #ff4d4d, #ff0000);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background: #fff;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

.login-container h2 {
    margin-bottom: 25px;
    color: #ff0000;
}

.login-container .form-group {
    margin-bottom: 15px;
    text-align: left;
}

.login-container label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.login-container input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.login-container input:focus {
    outline: none;
    border-color: #ff4d4d;
    box-shadow: 0 0 5px rgba(255,77,77,0.5);
}

.login-container button {
    width: 100%;
    padding: 12px;
    background: #ff0000;
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
    transition: 0.3s;
}

.login-container button:hover {
    background: #e60000;
}

.error {
    color: red;
    margin-bottom: 15px;
}

.success {
    color: green;
    margin-bottom: 15px;
}

.login-container a {
    display: inline-block;
    margin-top: 15px;
    color: #ff0000;
    text-decoration: none;
}

.login-container a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="login-container">
    <h2>เข้าสู่ระบบ</h2>

    <?php if(!empty($errors)): ?>
        <div class="error">
            <?php foreach($errors as $error) echo $error."<br>"; ?>
        </div>
    <?php endif; ?>

<div style="position: absolute; top: 20px; left: 20px; display: flex; gap: 15px;">
    <a class="back-link" href="index.php" style=  "margin-top: 30px; color: #fff; margin-bottom: 10px; display: inline-block;">
        <i class="fas fa-arrow-left"></i> กลับหน้าแรก
  </a>
</div>

    <form action="" method="post">
        <div class="form-group">
            <label>ชื่อผู้ใช้</label>
            <input type="text" name="username" value="<?php echo isset($username)?htmlspecialchars($username):''; ?>" required>
        </div>
        <div class="form-group">
            <label>รหัสผ่าน</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">เข้าสู่ระบบ</button>
    </form>

    <a href="signup.php">ยังไม่มีบัญชี? สมัครสมาชิก</a>
</div>

</body>
</html>

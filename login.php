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
            $_SESSION['user_id'] = $user['id']; // Store user ID
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
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #000;
    color: #f5f5f7;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    box-sizing: border-box;
}

.login-container {
    background: #1c1c1e;
    padding: 40px;
    border-radius: 20px;
    border: 1px solid #38383a;
    width: 100%;
    max-width: 420px;
    text-align: center;
}

.login-container h2 {
    margin-top: 0;
    margin-bottom: 25px;
    color: #f5f5f7;
    font-size: 28px;
    font-weight: 600;
}

.login-container .form-group {
    margin-bottom: 20px;
    text-align: left;
}

.login-container label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #a1a1a6;
    font-size: 14px;
}

.login-container input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #444;
    background: #2c2c2e;
    color: #f5f5f7;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.login-container input:focus {
    outline: none;
    border-color: #0071e3;
    box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.3);
}

.login-container button {
    width: 100%;
    padding: 14px;
    background: #0071e3;
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.2s;
}

.login-container button:hover {
    background: #147ce5;
}

.error-box {
    color: #ff453a;
    background-color: rgba(255, 69, 58, 0.1);
    border: 1px solid rgba(255, 69, 58, 0.3);
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: left;
    font-size: 14px;
}
.error-box ul {
    margin: 0;
    padding-left: 20px;
}

.bottom-links {
    margin-top: 25px;
    font-size: 14px;
}
.bottom-links a {
    color: #0071e3;
    text-decoration: none;
    transition: color 0.2s;
}
.bottom-links a:hover {
    color: #147ce5;
}
.bottom-links .separator {
    margin: 0 10px;
    color: #555;
}
</style>
</head>
<body>

<div class="login-container">
    <h2>เข้าสู่ระบบ</h2>

    <?php if(!empty($errors)): ?>
        <div class="error-box">
            <ul>
            <?php foreach($errors as $error) echo "<li>".$error."</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post">
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

    <div class="bottom-links">
        <a href="signup.php">ยังไม่มีบัญชี? สมัครสมาชิก</a>
        <span class="separator">|</span>
        <a href="index.php">กลับสู่หน้าแรก</a>
    </div>
</div>

</body>
</html>
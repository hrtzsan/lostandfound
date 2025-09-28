<?php
session_start();
include 'db.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ากด submit หรือยัง
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบความถูกต้องเบื้องต้น
    $errors = [];

    if(empty($username)) $errors[] = "กรุณากรอกชื่อผู้ใช้";
    if(empty($email)) $errors[] = "กรุณากรอกอีเมล";
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "รูปแบบอีเมลไม่ถูกต้อง";
    if(empty($password)) $errors[] = "กรุณากรอกรหัสผ่าน";
    if($password !== $confirm_password) $errors[] = "รหัสผ่านไม่ตรงกัน";

    // ตรวจสอบว่าผู้ใช้ซ้ำหรือไม่
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $stmt->execute([$username, $email]);
    if($stmt->rowCount() > 0) $errors[] = "ชื่อผู้ใช้หรืออีเมลนี้มีอยู่แล้ว";

    // ถ้าไม่มี error ให้เพิ่มในฐานข้อมูล
    if(empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if($insert->execute([$username, $email, $hashed_password])) {
            $_SESSION['username'] = $username; // สร้าง session
            $_SESSION['success'] = "สมัครสมาชิกสำเร็จ!";
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>สมัครสมาชิก - Lost & Found</title>
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

.signup-container {
    background: #fff;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

.signup-container h2 {
    margin-bottom: 25px;
    color: #ff0000;
}

.signup-container .form-group {
    margin-bottom: 15px;
    text-align: left;
}

.signup-container label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.signup-container input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.signup-container input:focus {
    outline: none;
    border-color: #ff4d4d;
    box-shadow: 0 0 5px rgba(255,77,77,0.5);
}

.signup-container button {
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

.signup-container button:hover {
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

.signup-container a {
    display: inline-block;
    margin-top: 15px;
    color: #ff0000;
    text-decoration: none;
}

.signup-container a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="signup-container">
    <h2>สมัครสมาชิก</h2>

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
            <label>อีเมล</label>
            <input type="email" name="email" value="<?php echo isset($email)?htmlspecialchars($email):''; ?>" required>
        </div>
        <div class="form-group">
            <label>รหัสผ่าน</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>ยืนยันรหัสผ่าน</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit">สมัครสมาชิก</button>
    </form>

    <a href="login.php">มีบัญชีแล้ว? เข้าสู่ระบบ</a>
</div>

</body>
</html>

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
    if(strlen($password) < 6) $errors[] = "รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร";
    if($password !== $confirm_password) $errors[] = "รหัสผ่านไม่ตรงกัน";

    // ตรวจสอบว่าผู้ใช้ซ้ำหรือไม่
    if(empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? OR email=?");
        $stmt->execute([$username, $email]);
        if($stmt->rowCount() > 0) $errors[] = "ชื่อผู้ใช้หรืออีเมลนี้มีอยู่ในระบบแล้ว";
    }

    // ถ้าไม่มี error ให้เพิ่มในฐานข้อมูล
    if(empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if($insert->execute([$username, $email, $hashed_password])) {
            $_SESSION['user_id'] = $pdo->lastInsertId(); // Store user ID as well
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

.signup-container {
    background: #1c1c1e;
    padding: 40px;
    border-radius: 20px;
    border: 1px solid #38383a;
    width: 100%;
    max-width: 420px;
    text-align: center;
}

.signup-container h2 {
    margin-top: 0;
    margin-bottom: 25px;
    color: #f5f5f7;
    font-size: 28px;
    font-weight: 600;
}

.signup-container .form-group {
    margin-bottom: 20px;
    text-align: left;
}

.signup-container label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #a1a1a6;
    font-size: 14px;
}

.signup-container input {
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

.signup-container input:focus {
    outline: none;
    border-color: #0071e3;
    box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.3);
}

.signup-container button {
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

.signup-container button:hover {
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

<div class="signup-container">
    <h2>สร้างบัญชีใหม่</h2>

    <?php if(!empty($errors)): ?>
        <div class="error-box">
            <ul>
            <?php foreach($errors as $error) echo "<li>".$error."</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="signup.php" method="post">
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

    <div class="bottom-links">
        <a href="login.php">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
        <span class="separator">|</span>
        <a href="index.php">กลับสู่หน้าแรก</a>
    </div>
</div>

</body>
</html>
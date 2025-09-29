<?php
session_start();
include 'db.php';

// 1. ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่ ถ้าไม่ ให้หยุดการทำงาน
if (!isset($_SESSION['user_id'])) {
    die("กรุณาเข้าสู่ระบบเพื่อแก้ไขโพสต์");
}

// 2. รับค่า id และ type จาก URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = isset($_GET['type']) ? $_GET['type'] : '';

if (!$id || !in_array($type, ['lost', 'found'])) {
    die("ไม่พบโพสต์ที่ต้องการแก้ไข");
}

// 3. ดึงข้อมูลโพสต์และตรวจสอบความเป็นเจ้าของ
$table = ($type == 'lost') ? 'lost_items' : 'found_items';
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// ---- SECURITY CHECK: ตรวจสอบว่าใช่เจ้าของโพสต์หรือไม่ ----
if (!$post || $post['user_id'] != $_SESSION['user_id']) {
    die("คุณไม่มีสิทธิ์แก้ไขโพสต์นี้");
}

// 4. เมื่อมีการกดบันทึกการแก้ไข
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลใหม่จากฟอร์ม
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $place = $_POST['place'];
    $contact_info = $_POST['contact_info'];
    $status = $_POST['status'];
    $image = $post['image']; // ใช้รูปเดิมเป็นค่าเริ่มต้น

    // อัปเดตรูปถ้ามีการอัปโหลดใหม่
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // ลบรูปเก่า (ถ้าไม่ใช่ default.png)
            if ($post['image'] && $post['image'] != 'default.png' && file_exists($targetDir . $post['image'])) {
                unlink($targetDir . $post['image']);
            }
            $image = $fileName; // อัปเดตเป็นรูปใหม่
        }
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    $date_col = ($type == 'lost') ? 'lost_date' : 'found_date';
    $place_col = ($type == 'lost') ? 'lost_place' : 'found_place';

    $sql = "UPDATE $table SET item_name=?, description=?, $date_col=?, $place_col=?, contact_info=?, status=?, image=? WHERE id=?";
    $update_stmt = $pdo->prepare($sql);
    
    if ($update_stmt->execute([$item_name, $description, $date, $place, $contact_info, $status, $image, $id])) {
        header("Location: post_detail.php?id=$id&type=$type");
        exit();
    } else {
        $error_message = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    }
}

// เตรียมข้อมูลสำหรับแสดงในฟอร์ม
$itemName = htmlspecialchars($post['item_name']);
$description = htmlspecialchars($post['description']);
$date = ($type == 'lost') ? $post['lost_date'] : $post['found_date'];
$place = htmlspecialchars(($type == 'lost') ? $post['lost_place'] : $post['found_place']);
$contactInfo = htmlspecialchars($post['contact_info']);
$current_status = $post['status'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขโพสต์ - <?= $itemName ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Poppins', sans-serif; background: #000; color: #f5f5f7; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 40px 20px; box-sizing: border-box; }
        .container { background: #1c1c1e; padding: 40px; border-radius: 20px; border: 1px solid #38383a; width: 100%; max-width: 600px; }
        h2 { margin-top: 0; margin-bottom: 30px; text-align: center; font-weight: 600; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #a1a1a6; font-size: 14px; }
        input[type="text"], input[type="date"], textarea, select { width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #444; background: #2c2c2e; color: #f5f5f7; font-size: 16px; box-sizing: border-box; }
        textarea { resize: vertical; min-height: 100px; }
        .current-img { margin-top: 10px; }
        .current-img p { font-size: 14px; color: #a1a1a6; margin-bottom: 10px; }
        .current-img img { max-width: 120px; border-radius: 8px; border: 1px solid #38383a; }
        .btn-group { margin-top: 30px; display: flex; gap: 15px; }
        .btn { flex: 1; padding: 12px; border: none; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 16px; cursor: pointer; text-align: center; transition: background-color 0.2s; }
        .btn-save { background: #0071e3; color: white; }
        .btn-save:hover { background: #147ce5; }
        .btn-cancel { background: #555; color: white; }
        .btn-cancel:hover { background: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2>แก้ไขโพสต์ <?= ($type == 'lost') ? 'ของหาย' : 'ของที่เจอ' ?></h2>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>ชื่อสิ่งของ</label>
                <input type="text" name="item_name" value="<?= $itemName ?>" required>
            </div>
            <div class="form-group">
                <label>รายละเอียด</label>
                <textarea name="description"><?= $description ?></textarea>
            </div>
            <div class="form-group">
                <label>วันที่<?= ($type == 'lost') ? 'หาย' : 'พบ' ?></label>
                <input type="date" name="date" value="<?= $date ?>" required>
            </div>
            <div class="form-group">
                <label>สถานที่<?= ($type == 'lost') ? 'หาย' : 'พบ' ?></label>
                <input type="text" name="place" value="<?= $place ?>" required>
            </div>
             <div class="form-group">
                <label>ข้อมูลติดต่อ</label>
                <input type="text" name="contact_info" value="<?= $contactInfo ?>" required>
            </div>
            <div class="form-group">
                <label>สถานะ</label>
                <select name="status">
                    <?php if ($type == 'lost'): ?>
                        <option value="lost" <?= ($current_status == 'lost') ? 'selected' : '' ?>>ยังหาไม่เจอ</option>
                        <option value="found" <?= ($current_status == 'found') ? 'selected' : '' ?>>เจอแล้ว</option>
                    <?php else: ?>
                        <option value="lost" <?= ($current_status == 'lost') ? 'selected' : '' ?>>ยังหาเจ้าของไม่เจอ</option>
                        <option value="found" <?= ($current_status == 'found') ? 'selected' : '' ?>>เจอเจ้าของแล้ว</option>
                    <?php endif; ?>
                </select>
            </div>
<div class="form-group">
    <label>เปลี่ยนรูปภาพ</label>
    <input type="file" name="image" accept="image/*">
    
    <?php 
    // แก้ไขเงื่อนไขตรงนี้:
    // ตรวจสอบว่ามีข้อมูลรูปภาพ และต้องไม่ใช่รูปภาพดีฟอลต์
    if (!empty($post['image']) && $post['image'] != 'default.png'): 
    ?>
        <div class="current-img">
            <p>รูปปัจจุบัน:</p>
            <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Current Image">
        </div>
    <?php 
    // ไม่ต้องมี else เพราะถ้าเงื่อนไขเป็นเท็จ ก็จะไม่แสดงอะไรเลย
    endif; 
    ?>
</div>

            <div class="btn-group">
                <button type="submit" class="btn btn-save">บันทึกการเปลี่ยนแปลง</button>
                <a href="post_detail.php?id=<?= $id ?>&type=<?= $type ?>" class="btn btn-cancel">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>
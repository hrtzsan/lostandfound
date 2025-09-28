<?php
include 'db.php';

// ตรวจสอบว่ามี id ส่งมาหรือไม่
if (!isset($_GET['id'])) {
    die("ไม่พบข้อมูลที่ต้องการแก้ไข");
}

$id = $_GET['id'];

// ดึงข้อมูลรายการที่ต้องการแก้ไข
$stmt = $pdo->prepare("SELECT * FROM found_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("ไม่พบข้อมูล");
}

// เมื่อกดบันทึกการแก้ไข
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $found_date = $_POST['found_date'];
    $found_place = $_POST['found_place'];
    $contact_info = $_POST['contact_info'];
    $status = $_POST['status'];

    // อัปเดตรูปถ้ามีการอัปโหลดใหม่
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $fileName;
        }
    } else {
        $image = $item['image']; // ถ้าไม่อัปโหลดใหม่ ใช้รูปเดิม
    }

    $update = $pdo->prepare("UPDATE found_items SET item_name=?, description=?, found_date=?, found_place=?, contact_info=?, status=?, image=? WHERE id=?");
    $update->execute([$item_name, $description, $found_date, $found_place, $contact_info, $status, $image, $id]);

    header("Location: found_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขข้อมูล</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    label {
      font-weight: bold;
      margin-top: 10px;
      display: block;
      color: #444;
    }
    input[type="text"], input[type="date"], textarea, select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
    }
    textarea {
      resize: vertical;
      min-height: 80px;
    }
    .btn {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
    }
    .btn button, .btn a {
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      cursor: pointer;
      transition: 0.2s;
    }
    .btn-save {
      background: #28a745;
      color: white;
    }
    .btn-save:hover {
      background: #218838;
    }
    .btn-cancel {
      background: #6c757d;
      color: white;
    }
    .btn-cancel:hover {
      background: #5a6268;
    }
    .current-img {
      margin-top: 10px;
      text-align: center;
    }
    .current-img img {
      max-width: 120px;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>แก้ไขข้อมูลของหาย</h2>
    <form method="post" enctype="multipart/form-data">
      <label>ชื่อสิ่งของ</label>
      <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>

      <label>รายละเอียด</label>
      <textarea name="description"><?= htmlspecialchars($item['description']) ?></textarea>

      <label>วันที่หาย</label>
      <input type="date" name="found_date" value="<?= $item['found_date'] ?>" required>

      <label>สถานที่</label>
      <input type="text" name="found_place" value="<?= htmlspecialchars($item['found_place']) ?>" required>

      <label>ข้อมูลติดต่อ</label>
      <input type="text" name="contact_info" value="<?= htmlspecialchars($item['contact_info']) ?>" required>

      <label>สถานะ</label>
      <select name="status" required>
        <option value="ยังหาเจ้าของไม่เจอ" <?= $item['status']=="ยังหาเจ้าของไม่เจอ" ? "selected" : "" ?>>ยังหาเจ้าของไม่เจอ</option>
        <option value="เจอเจ้าของแล้ว" <?= $item['status']=="เจอเจ้าของแล้ว" ? "selected" : "" ?>>เจอเจ้าของแล้ว</option>
      </select>

      <label>เปลี่ยนรูป</label>
      <input type="file" name="image" accept="image/*">
      
      <div class="current-img">
        <?php if($item['image']): ?>
          <p>รูปปัจจุบัน:</p>
          <img src="uploads/<?= $item['image'] ?>" alt="">
        <?php endif; ?>
      </div>

      <div class="btn">
        <button type="submit" class="btn-save">บันทึก</button>
        <a href="index.php" class="btn-cancel">ยกเลิก</a>
      </div>
    </form>
  </div>
</body>
</html>
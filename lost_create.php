<?php
session_start(); // <<< 1. ต้องเริ่ม session ก่อนเสมอ
include 'db.php'; // ใช้ $pdo

// <<< 2. เพิ่มการตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    // ถ้ายังไม่ล็อกอิน ให้หยุดการทำงานและแจ้งเตือน
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการโพสต์'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['lost_title'];
    $description = $_POST['lost_description'];
    $lost_date = $_POST['lostDate'];
    $lost_place = $_POST['lost_location'];
    $contact_info = $_POST['lost_contact'];
    $status = $_POST['lost_status'];
    $user_id = $_SESSION['user_id']; // <<< 3. ดึง user_id ของคนที่ล็อกอินอยู่มาเก็บไว้

    // อัพโหลดรูปภาพ
    $image = 'default.png'; // กำหนดภาพเริ่มต้น เผื่อไม่มีการอัปโหลด
    if (isset($_FILES['lost_item_image']) && $_FILES['lost_item_image']['error'] === 0) {
        $image_name = time() . '_' . basename($_FILES['lost_item_image']['name']);
        $target_dir = "uploads/";
        if (move_uploaded_file($_FILES['lost_item_image']['tmp_name'], $target_dir . $image_name)) {
            $image = $image_name;
        }
    }

    try {
        // <<< 4. เพิ่ม user_id เข้าไปในคำสั่ง INSERT
        $stmt = $pdo->prepare("INSERT INTO lost_items 
            (user_id, item_name, description, lost_date, lost_place, contact_info, status, image) 
            VALUES (:user_id, :item_name, :description, :lost_date, :lost_place, :contact_info, :status, :image)");

        // <<< 5. เพิ่ม :user_id เข้าไปใน execute array
        $stmt->execute([
            ':user_id' => $user_id,
            ':item_name' => $item_name,
            ':description' => $description,
            ':lost_date' => $lost_date,
            ':lost_place' => $lost_place,
            ':contact_info' => $contact_info,
            ':status' => $status,
            ':image' => $image
        ]);

        // แจ้งเตือนและกลับไปหน้า index.php
        echo "<script>alert('โพสต์แจ้งของหายสำเร็จ!'); window.location.href='index.php';</script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }
}
?>
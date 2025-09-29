<?php
session_start(); // <<< 1. ต้องเริ่ม session ก่อนเสมอ
include 'db.php'; // นี่จะมีตัวแปร $pdo

// <<< 2. เพิ่มการตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการโพสต์'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['found_title'];
    $description = $_POST['found_description'];
    $found_date = $_POST['foundDate'];
    $found_place = $_POST['found_location'];
    $contact_info = $_POST['found_contact'];
    $status = $_POST['found_status'];
    $user_id = $_SESSION['user_id']; // <<< 3. ดึง user_id จาก session

    // อัพโหลดรูปภาพ
    $image = 'default.png';
    if (isset($_FILES['found_item_image']) && $_FILES['found_item_image']['error'] === 0) {
        $image_name = time() . '_' . basename($_FILES['found_item_image']['name']);
        $target_dir = "uploads/";
        if (move_uploaded_file($_FILES['found_item_image']['tmp_name'], $target_dir . $image_name)) {
            $image = $image_name;
        }
    }

    // เพิ่ม try...catch ให้ครอบคลุม
    try {
        // <<< 4. เพิ่ม user_id เข้าไปในคำสั่ง INSERT
        $stmt = $pdo->prepare("INSERT INTO found_items (user_id, item_name, description, found_date, found_place, contact_info, status, image) 
                                VALUES (:user_id, :item_name, :description, :found_date, :found_place, :contact_info, :status, :image)");

        // <<< 5. เพิ่ม :user_id เข้าไปใน execute array
        $stmt->execute([
            ':user_id' => $user_id,
            ':item_name' => $item_name,
            ':description' => $description,
            ':found_date' => $found_date,
            ':found_place' => $found_place,
            ':contact_info' => $contact_info,
            ':status' => $status,
            ':image' => $image
        ]);

        // <<< 6. แก้ไขข้อความแจ้งเตือนให้ถูกต้อง
        echo "<script>alert('โพสต์แจ้งของที่เจอสำเร็จ!'); window.location.href='index.php';</script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }
}
?>
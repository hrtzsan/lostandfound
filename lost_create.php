<?php
include 'db.php'; // ใช้ $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['lost_title'];
    $description = $_POST['lost_description'];
    $lost_date = $_POST['lostDate'];
    $lost_place = $_POST['lost_location'];
    $contact_info = $_POST['lost_contact'];
    $status = $_POST['lost_status'];

    // อัพโหลดรูปภาพ
    $image = '';
    if (isset($_FILES['lost_item_image']) && $_FILES['lost_item_image']['error'] === 0) {
        $image = basename($_FILES['lost_item_image']['name']);
        $target_dir = "uploads/";
        move_uploaded_file($_FILES['lost_item_image']['tmp_name'], $target_dir . $image);
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO lost_items 
            (item_name, description, lost_date, lost_place, contact_info, status, image) 
            VALUES (:item_name, :description, :lost_date, :lost_place, :contact_info, :status, :image)");

        $stmt->execute([
            ':item_name' => $item_name,
            ':description' => $description,
            ':lost_date' => $lost_date,
            ':lost_place' => $lost_place,
            ':contact_info' => $contact_info,
            ':status' => $status,
            ':image' => $image
        ]);

        // แจ้งเตือนและกลับไปหน้า lost_list.php
        echo "<script>alert('โพสต์แจ้งของหายสำเร็จ!'); window.location.href='index.php';</script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }
}
?>
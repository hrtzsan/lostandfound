<?php
session_start();
include 'db.php'; // PDO connection: $pdo

// รับค่า id และ type
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 'lost';

if(!$id){
    die("ไม่พบโพสต์ที่ต้องการลบ");
}

$table = ($type == 'lost') ? 'lost_items' : 'found_items';

// ตรวจสอบว่าเจ้าของโพสต์เท่านั้นที่สามารถลบได้
try {
    $stmt = $pdo->prepare("SELECT user_id, image FROM $table WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$post){
        die("ไม่พบโพสต์นี้");
    }

    if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $post['user_id']){
        die("คุณไม่มีสิทธิ์ลบโพสต์นี้");
    }

    // ลบรูปภาพจากโฟลเดอร์ (ถ้ามี)
    if(!empty($post['image']) && file_exists("uploads/".$post['image'])){
        unlink("uploads/".$post['image']);
    }

    // ลบโพสต์จากฐานข้อมูล
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // ลบเสร็จแล้วกลับไปหน้ารวมโพสต์
    header("Location: all_posts.php?msg=deleted");
    exit;

} catch(PDOException $e){
    die("เกิดข้อผิดพลาด: ".$e->getMessage());
}
?>
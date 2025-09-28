<?php
include 'db.php'; // ต้องแน่ใจว่าใน db.php สร้าง $pdo

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM found_items WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: found_list.php"); // กลับไปหน้ารายการ
        exit;
    } catch (PDOException $e) {
        die("เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage());
    }
} else {
    die("ไม่พบ ID ที่จะลบ");
}

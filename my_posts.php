<?php
session_start();
include 'db.php';

// Security: ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่ ถ้าไม่ ให้ไปหน้า login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // ดึงข้อมูล "ของหาย" ของผู้ใช้คนนี้
    $lost_stmt = $pdo->prepare("SELECT * FROM lost_items WHERE user_id = ? ORDER BY lost_date DESC");
    $lost_stmt->execute([$user_id]);
    $lost_posts = $lost_stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูล "ของที่เจอ" ของผู้ใช้คนนี้
    $found_stmt = $pdo->prepare("SELECT * FROM found_items WHERE user_id = ? ORDER BY found_date DESC");
    $found_stmt->execute([$user_id]);
    $found_posts = $found_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage());
}

// ฟังก์ชันแปลงสถานะ
function status_text($status, $type) {
    if ($type == 'lost') {
        return ($status == 'lost') ? 'ยังไม่เจอ' : 'เจอแล้ว';
    } else {
        return ($status == 'lost') ? 'ยังหาเจ้าของไม่เจอ' : 'เจอเจ้าของแล้ว';
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>โพสต์ของฉัน - Lost & Found</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Poppins', sans-serif; background: #000; color: #f5f5f7; padding: 20px; margin: 0; }
    .container { max-width: 1200px; margin: 0 auto; }
    h2 { text-align: center; margin: 20px 0 30px 0; font-size: 28px; }
    .section-title { margin-top: 40px; margin-bottom: 20px; font-size: 24px; border-bottom: 1px solid #38383a; padding-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; background: #1c1c1e; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #38383a; }
    th { background: #2c2c2e; font-weight: 600; }
    tr:hover { background: #252527; }
    .btn { padding: 6px 12px; border-radius: 5px; font-size: 14px; text-decoration: none; margin: 2px; display: inline-block; transition: 0.2s; color: white; border: none; cursor: pointer; }
    .btn-edit { background: #0071e3; }
    .btn-delete { background: #c70000; }
    .btn-edit:hover { background: #147ce5; }
    .btn-delete:hover { background: #ff453a; }
    .action-cell { min-width: 130px; text-align: center; }
    .back-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; color: #0071e3; font-weight: 500; }
    .back-link:hover { color:#147ce5; }
    .no-posts { text-align: center; color: #a1a1a6; padding: 30px; background: #1c1c1e; border-radius: 8px; }
</style>
</head>
<body>

<div class="container">
    <a class="back-link" href="index.php"><i class="fas fa-arrow-left"></i> กลับหน้าแรก</a>
    <h2>โพสต์ทั้งหมดของฉัน</h2>

    <h3 class="section-title">ของหายที่คุณโพสต์</h3>
    <?php if (empty($lost_posts)): ?>
        <p class="no-posts">คุณยังไม่มีโพสต์ของหาย</p>
    <?php else: ?>
    <table>
        <tr><th>ชื่อสิ่งของ</th><th>วันที่หาย</th><th>สถานะ</th><th style="text-align: center;">จัดการ</th></tr>
        <?php foreach($lost_posts as $post): ?>
        <tr>
            <td><?= htmlspecialchars($post['item_name']) ?></td>
            <td><?= htmlspecialchars($post['lost_date']) ?></td>
            <td><?= status_text($post['status'], 'lost') ?></td>
            <td class="action-cell">
                <a href="edit_post.php?id=<?= $post['id'] ?>&type=lost" class="btn btn-edit">แก้ไข</a>
                <a href="delete_post.php?id=<?= $post['id'] ?>&type=lost" class="btn btn-delete" onclick="return confirm('คุณแน่ใจว่าจะลบรายการนี้?');">ลบ</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <h3 class="section-title">ของที่เจอที่คุณโพสต์</h3>
    <?php if (empty($found_posts)): ?>
        <p class="no-posts">คุณยังไม่มีโพสต์ของที่เจอ</p>
    <?php else: ?>
    <table>
        <tr><th>ชื่อสิ่งของ</th><th>วันที่พบ</th><th>สถานะ</th><th style="text-align: center;">จัดการ</th></tr>
        <?php foreach($found_posts as $post): ?>
        <tr>
            <td><?= htmlspecialchars($post['item_name']) ?></td>
            <td><?= htmlspecialchars($post['found_date']) ?></td>
            <td><?= status_text($post['status'], 'found') ?></td>
            <td class="action-cell">
                <a href="edit_post.php?id=<?= $post['id'] ?>&type=found" class="btn btn-edit">แก้ไข</a>
                <a href="delete_post.php?id=<?= $post['id'] ?>&type=found" class="btn btn-delete" onclick="return confirm('คุณแน่ใจว่าจะลบรายการนี้?');">ลบ</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

</div>

</body>
</html>
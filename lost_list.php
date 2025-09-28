<?php
include 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM lost_items ORDER BY created_at DESC");
    $lost_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = count($lost_items);
} catch (PDOException $e) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage());
}

// ฟังก์ชันแปลงสถานะเป็นข้อความไทย
function status_text($status) {
    if ($status === 'lost') return 'ยังหาเจ้าของไม่เจอ';
    if ($status === 'found') return 'เจอเจ้าของแล้ว';
    return $status;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายการของหาย</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f7f7f7;
    padding: 20px;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}
th, td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}
th {
    background: #4CAF50;
    color: white;
    font-weight: bold;
}
tr:nth-child(even) { background: #fafafa; }
tr:hover { background: #f1f1f1; }
img {
    border-radius: 5px;
    max-width: 80px;
    height: auto;
}
.btn {
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 14px;
    text-decoration: none;
    margin: 2px;
    display: inline-block;
    transition: 0.2s;
}
.btn-edit { background: #ffa500; color: white; }
.btn-delete { background: #e74c3c; color: white; }
.btn-edit:hover { background: #ff8c00; }
.btn-delete:hover { background: #c0392b; }
</style>
</head>
<body>

<h2>รายการของหาย</h2>

<table>
<tr>
    <th>ลำดับ</th>
    <th>ชื่อ</th>
    <th>รายละเอียด</th>
    <th>วันที่</th>
    <th>สถานที่</th>
    <th>ติดต่อ</th>
    <th>สถานะ</th>
    <th>รูป</th>
    <th>จัดการ</th>
</tr>

<?php foreach($lost_items as $item): ?>
<tr>
    <td><?= $total-- ?></td>
    <td><?= htmlspecialchars($item['item_name']) ?></td>
    <td><?= htmlspecialchars($item['description']) ?></td>
    <td><?= htmlspecialchars($item['lost_date']) ?></td>
    <td><?= htmlspecialchars($item['lost_place']) ?></td>
    <td><?= htmlspecialchars($item['contact_info']) ?></td>
    <td><?= status_text($item['status']) ?></td>
    <td>
        <?php if($item['image']): ?>
        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="รูปสิ่งของ">
        <?php endif; ?>
    </td>
    <td>
        <a href="lost_edit.php?id=<?= $item['id'] ?>" class="btn btn-edit">แก้ไข</a>
        <a href="lost_delete.php?id=<?= $item['id'] ?>" class="btn btn-delete" onclick="return confirm('คุณแน่ใจว่าจะลบรายการนี้?');">ลบ</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
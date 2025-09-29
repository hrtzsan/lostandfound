<?php
session_start(); // <<< 1. ต้องเริ่ม session ก่อนเสมอ เพื่อเช็คว่าใครล็อกอินอยู่
include 'db.php'; // PDO connection: $pdo

// รับค่าพารามิเตอร์
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 'lost'; // lost หรือ found

if(!$id){
    die("ไม่พบโพสต์ที่ต้องการ");
}

$table = ($type=='lost') ? 'lost_items' : 'found_items';

try{
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = :id");
    $stmt->execute(['id'=>$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$post){
        die("ไม่พบโพสต์นี้");
    }
} catch(PDOException $e){
    die("เกิดข้อผิดพลาด: ".$e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายละเอียดโพสต์ - <?php echo htmlspecialchars($post['item_name']); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { 
    font-family: 'Poppins', sans-serif; 
    margin:0; 
    padding:0; 
    background:#000; 
    color:#e0e0e0; 
}
a { text-decoration:none; color:inherit; }
.container { max-width:900px; margin:40px auto; padding:20px; }
.back-link { 
    display:inline-flex; 
    align-items: center;
    gap: 8px;
    margin-bottom:20px; 
    color:#0071e3; 
    font-weight:500; 
    transition: color 0.2s; 
}
.back-link:hover { color:#147ce5; }

/* <<< 2. เพิ่ม CSS สำหรับปุ่มแก้ไข */
.edit-button {
    display: inline-block;
    padding: 8px 15px;
    background-color: #0071e3; /* Blue accent color */
    color: white;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    margin-bottom: 20px;
    text-decoration: none;
    transition: background-color 0.2s;
}
.edit-button:hover {
    background-color: #147ce5;
}
.edit-button i {
    margin-right: 6px;
}

/* Card */
.post-card { 
    display:flex; 
    flex-direction:column; 
    background:#1c1c1e; 
    border-radius:12px; 
    border: 1px solid #38383a;
    overflow:hidden; 
}
.post-card img { 
    width:100%; 
    height:350px; 
    object-fit:cover; 
    border-bottom: 1px solid #38383a;
}
.post-content { padding:30px; }
.post-content h2 { 
    margin-top:0; 
    margin-bottom: 20px;
    display:flex; 
    align-items:center; 
    gap:12px; 
    font-size:26px; 
    font-weight: 600;
    color:<?php echo ($type=='lost')?'#ff453a':'#32d74b'; ?>;
}
.post-content p { 
    margin:15px 0; 
    line-height:1.7; 
    font-size:16px; 
    color: #a1a1a6;
}
.post-content p strong {
    color: #e0e0e0;
}

/* Contact Card */
.contact-card { 
    margin-top:25px; 
    padding:20px; 
    background:#2c2c2e; 
    border-left:5px solid <?php echo ($type=='lost')?'#ff453a':'#32d74b'; ?>; 
    border-radius:0 8px 8px 0; 
}
.contact-card h3 { 
    margin-top:0; 
    font-size:18px; 
    display:flex; 
    align-items:center; 
    gap:10px; 
    color: #f5f5f7; 
}
.contact-card p { 
    margin:8px 0; 
    word-wrap:break-word; 
    color: #a1a1a6;
}

/* Contact Buttons */
.contact-buttons { margin-top:15px; display:flex; gap:10px; flex-wrap:wrap; }
.contact-button { 
    display:inline-flex; 
    align-items:center; 
    gap: 8px; 
    padding:10px 20px; 
    font-weight:500; 
    border:none; 
    border-radius:8px; 
    color:white; 
    transition: background-color 0.2s; 
    cursor:pointer; 
    text-align:center;
}
.contact-button.line { background:#00c300; }
.contact-button.line:hover { background:#009900; }

/* Responsive */
@media (min-width:768px){
    .post-card { flex-direction: row; }
    .post-card img { 
        width:45%; 
        height:auto; 
        border-right: 1px solid #38383a;
        border-bottom: none;
    }
    .post-content { width:55%; }
}
</style>
</head>
<body>

<div class="container">

<a class="back-link" href="all_posts.php"><i class="fas fa-arrow-left"></i> กลับไปหน้ารวมโพสต์</a>

<div class="post-card">

    <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['item_name']); ?>" onerror="this.src='assets/placeholder.png';">

    <div class="post-content">
        <h2>
            <?php echo ($type=='lost') ? '<i class="fas fa-thumbtack"></i>' : '<i class="fas fa-box"></i>'; ?>
            <?php echo htmlspecialchars($post['item_name']); ?>
        </h2>

        <?php 
        // <<< 3. เพิ่มโค้ดตรวจสอบและแสดงปุ่มแก้ไข
        // ตรวจสอบว่าผู้ใช้ล็อกอินอยู่ และเป็นเจ้าของโพสต์นี้หรือไม่
        if (isset($_SESSION['user_id']) && isset($post['user_id']) && $_SESSION['user_id'] == $post['user_id']): 
        ?>
            <a href="edit_post.php?id=<?= $post['id'] ?>&type=<?= $type ?>" class="edit-button">
                <i class="fas fa-edit"></i> แก้ไขโพสต์
            </a>
        <?php 
        endif; 
        ?>

        <?php if($type=='lost'): ?>
            <p><strong>วันที่หาย:</strong> <?php echo $post['lost_date']; ?></p>
            <p><strong>สถานที่หาย:</strong> <?php echo htmlspecialchars($post['lost_place']); ?></p>
        <?php else: ?>
            <p><strong>วันที่พบ:</strong> <?php echo $post['found_date']; ?></p>
            <p><strong>สถานที่พบ:</strong> <?php echo htmlspecialchars($post['found_place']); ?></p>
        <?php endif; ?>

        <p><strong>รายละเอียด:</strong> <?php echo nl2br(htmlspecialchars($post['description'])); ?></p>

        <?php if(!empty($post['contact_info']) || !empty($post['line_id'])): ?>
        <div class="contact-card">
            <h3><i class="fas fa-address-book"></i> ติดต่อเจ้าของ/ผู้พบ</h3>
            <?php if(!empty($post['contact_info'])): ?>
            <p><?php echo nl2br(htmlspecialchars($post['contact_info'])); ?></p>
            <?php endif; ?>
            <div class="contact-buttons">
                <?php if(!empty($post['line_id'])): ?>
                <a class="contact-button line" href="https://line.me/ti/p/<?php echo htmlspecialchars($post['line_id']); ?>" target="_blank">
                    <i class="fab fa-line"></i> แชทไลน์
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

</div>
</body>
</html>
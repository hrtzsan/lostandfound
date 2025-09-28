<?php
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

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mitr:wght@400;500;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { font-family: 'Mitr','Poppins','Roboto',sans-serif; margin:0; padding:0; background:#f0f2f5; color:#333; }
a { text-decoration:none; color:inherit; }
.container { max-width:900px; margin:auto; padding:20px; }
.back-link { display:inline-block; margin-bottom:20px; color:#ff4d4d; font-weight:500; transition:0.2s; }
.back-link:hover { color:#ff0000; text-decoration:underline; }

/* Card */
.post-card { display:flex; flex-direction:column; background:white; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.1); overflow:hidden; transition:0.2s; }
.post-card:hover { box-shadow:0 10px 25px rgba(0,0,0,0.15); }
.post-card img { width:100%; height:350px; object-fit:cover; }
.post-content { padding:25px; }
.post-content h2 { margin-top:0; display:flex; align-items:center; gap:10px; font-size:24px; color:<?php echo ($type=='lost')?'#ff4d4d':'#00b300'; ?>; }
.post-content p { margin:12px 0; line-height:1.6; font-size:16px; }

/* Contact Card */
.contact-card { margin-top:25px; padding:20px; background:#f9f9f9; border-left:5px solid <?php echo ($type=='lost')?'#ff4d4d':'#00b300'; ?>; border-radius:10px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
.contact-card h3 { margin-top:0; font-size:18px; display:flex; align-items:center; gap:8px; }
.contact-card p { margin:8px 0; word-wrap:break-word; }

/* Contact Buttons */
.contact-buttons { margin-top:15px; display:flex; gap:10px; flex-wrap:wrap; }
.contact-button { display:inline-block; padding:12px 20px; font-weight:500; border:none; border-radius:8px; color:white; transition:0.2s; cursor:pointer; text-align:center; text-decoration:none; }
.contact-button.email { background:#ff4d4d; }
.contact-button.email:hover { background:#ff0000; }
.contact-button.call { background:#00b300; }
.contact-button.call:hover { background:#008000; }
.contact-button.line { background:#00c300; }
.contact-button.line:hover { background:#009900; }

/* Responsive */
@media (min-width:768px){
    .post-card { flex-direction: row; }
    .post-card img { width:50%; height:auto; }
    .post-content { width:50%; }
}
</style>
</head>
<body>

<div class="container">

<a class="back-link" href="all_posts.php"><i class="fas fa-arrow-left"></i> กลับไปหน้ารวมโพสต์</a>

<div class="post-card">

    <!-- รูป -->
    <img src="uploads/<?php echo $post['image']; ?>" alt="<?php echo htmlspecialchars($post['item_name']); ?>">

    <!-- เนื้อหา -->
    <div class="post-content">
        <h2>
            <?php echo ($type=='lost') ? '<i class="fas fa-thumbtack"></i>' : '<i class="fas fa-box"></i>'; ?>
            <?php echo htmlspecialchars($post['item_name']); ?>
        </h2>

        <?php if($type=='lost'): ?>
            <p><strong>วันที่หาย:</strong> <?php echo $post['lost_date']; ?></p>
            <p><strong>สถานที่หาย:</strong> <?php echo htmlspecialchars($post['lost_place']); ?></p>
        <?php else: ?>
            <p><strong>วันที่พบ:</strong> <?php echo $post['found_date']; ?></p>
            <p><strong>สถานที่พบ:</strong> <?php echo htmlspecialchars($post['found_place']); ?></p>
        <?php endif; ?>

        <p><strong>รายละเอียด:</strong> <?php echo nl2br(htmlspecialchars($post['description'])); ?></p>

        <!-- Contact -->
        <?php if(!empty($post['contact_info']) || !empty($post['line_id'])): ?>
        <div class="contact-card">
            <h3><i class="fas fa-address-book"></i> ติดต่อเจ้าของ/ผู้พบ</h3>

            <?php if(!empty($post['contact_info'])): ?>
            <p><?php echo nl2br(htmlspecialchars($post['contact_info'])); ?></p>
            <?php endif; ?>

            <div class="contact-buttons">
                <?php if(!empty($post['contact_info'])): ?>
                <a class="contact-button email" href="mailto:<?php echo htmlspecialchars($post['contact_info']); ?>">
                    <i class="fas fa-envelope"></i> ส่งอีเมล
                </a>
                <a class="contact-button call" href="tel:<?php echo htmlspecialchars($post['contact_info']); ?>">
                    <i class="fas fa-phone"></i> โทร
                </a>
                <?php endif; ?>

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

<?php
include 'db.php'; // PDO connection: $pdo

// รับค่าค้นหา
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$type   = isset($_GET['type']) ? $_GET['type'] : 'all';
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>All Posts - Lost & Found</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mitr:wght@400;500;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { font-family: 'Mitr', 'Poppins', 'Roboto', sans-serif; margin:0; padding:0; background:#f0f2f5; }
h1 { text-align:center; margin:30px 0; color:#333; }

/* Container */
.container { max-width:1200px; margin:auto; padding:0 20px; }

/* Back Button */
.back-button { display:inline-block; margin-bottom:20px; padding:10px 20px; background:#ff4d4d; color:white; border-radius:8px; font-weight:500; text-decoration:none; transition:0.2s; }
.back-button:hover { background:#ff0000; }

/* Search */
.search-form {
  text-align:center;
  margin-bottom:30px;
  display:flex;
  justify-content:center;
  gap:10px;
  flex-wrap:nowrap; /* เปลี่ยนจาก wrap เป็น nowrap */
  align-items:center; /* เพิ่มให้อยู่กลางแนวตั้ง */
}

.search-form input {
  padding:12px 15px;
  flex:2;             /* ให้ขยายตาม flex ของ form */
  min-width:200px;    /* กำหนดขั้นต่ำ */
  font-size:16px;
  border-radius:5px;
  border:1px solid #ccc;
}

.search-form select {
  font-size:16px;
  border-radius:5px;
  border:1px solid #ccc;
  height:44px;
  max-width:130px;
}

.search-form button {
  padding:12px 25px;
  font-size:16px;
  border:none;
  border-radius:5px;
  background:#ff0000;
  color:white;
  cursor:pointer;
  height:44px;
}


/* Columns */
.columns { display:flex; flex-wrap:wrap; gap:20px; justify-content:space-between; }

/* Each column */
.column { flex:1; min-width:300px; }

/* Grid inside column */
.grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; }

/* Card */
.card { background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s; cursor:pointer; text-decoration:none; color:inherit; }
.card:hover { transform: translateY(-4px); box-shadow:0 10px 25px rgba(0,0,0,0.2); }
.card img { width:100%; height:150px; object-fit:cover; }
.card-content { padding:12px; }
.card-content h3 { margin:0 0 5px 0; font-size:16px; display:flex; align-items:center; gap:6px; }
.card-content p { margin:3px 0; font-size:13px; color:#555; }
.type-lost h3 { color:#ff4d4d; }
.type-found h3 { color:#00b300; }

/* Section title */
.section-title { margin-bottom:10px; font-size:18px; font-weight:500; display:flex; align-items:center; gap:6px; }
</style>
</head>
<body>

<div class="container">

  <!-- Back to Home -->
  <a class="back-link" href="index.php" style="margin-top: 30px; margin-botto: 10px; display: inline-block;">
  <i class="fas fa-arrow-left"></i> กลับหน้าแรก
</a>

  <h1>โพสต์ทั้งหมด</h1>

<!-- Search Form -->
<form class="search-form" method="get" action="all_posts.php" 
      style="display:flex; gap:10px; align-items:center; justify-content:center; max-width:900px; margin:30px auto 0 auto; flex-wrap:nowrap;">
    
    <!-- Search input -->
    <input type="text" name="q" placeholder="ค้นหาของหาย / ของที่เจอ..." 
           value="<?php echo htmlspecialchars($search); ?>" 
           style="flex:2; padding:12px 15px; font-size:16px; border:1px solid #ccc; border-radius:5px; min-width:200px;">

    <!-- Dropdown เลือกประเภท -->
    <select name="type" style="font-size:16px; border:1px solid #ccc; border-radius:5px; height:44px; max-width:130px;">
      <option value="all" <?php if($type=='all') echo 'selected'; ?>>โพสต์ทั้งหมด</option>
      <option value="lost" <?php if($type=='lost') echo 'selected'; ?>>ของหาย</option>
      <option value="found" <?php if($type=='found') echo 'selected'; ?>>พบของหาย</option>
    </select>

    <!-- Submit button -->
    <button type="submit" style="padding:12px 25px; font-size:16px; background:#ff0000; color:white; border:none; border-radius:5px; cursor:pointer; height:44px;">
      <i class="fas fa-search"></i> ค้นหา
    </button>

</form>



  <div class="columns">

    <!-- Lost Items Column -->
    <div class="column">
      <div class="section-title"><i class="fas fa-thumbtack" style="color:#ff4d4d;"></i> ของหาย</div>
      <div class="grid">
      <?php
        try{
          if($search){
            $stmt = $pdo->prepare("SELECT * FROM lost_items WHERE item_name LIKE :search ORDER BY lost_date DESC");
            $stmt->execute(['search'=>"%$search%"]);
          } else {
            $stmt = $pdo->query("SELECT * FROM lost_items ORDER BY lost_date DESC");
          }

          if($stmt->rowCount() > 0){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "
              <a class='card type-lost' href='post_detail.php?id={$row['id']}&type=lost'>
                <img src='uploads/{$row['image']}' alt='{$row['item_name']}'>
                <div class='card-content'>
                  <h3><i class='fas fa-thumbtack'></i> {$row['item_name']}</h3>
                  <p><strong>วันที่หาย:</strong> {$row['lost_date']}</p>
                  <p><strong>สถานที่:</strong> {$row['lost_place']}</p>
                </div>
              </a>
              ";
            }
          } else {
            echo "<p style='color:#999;'>ยังไม่มีโพสต์ของหาย</p>";
          }

        }catch(PDOException $e){
          echo "<p style='color:red;'>เกิดข้อผิดพลาด: ".$e->getMessage()."</p>";
        }
      ?>
      </div>
    </div>

    <!-- Found Items Column -->
    <div class="column">
      <div class="section-title"><i class="fas fa-box" style="color:#00b300;"></i> ของที่เจอ</div>
      <div class="grid">
      <?php
        try{
          if($search){
            $stmt = $pdo->prepare("SELECT * FROM found_items WHERE item_name LIKE :search ORDER BY found_date DESC");
            $stmt->execute(['search'=>"%$search%"]);
          } else {
            $stmt = $pdo->query("SELECT * FROM found_items ORDER BY found_date DESC");
          }

          if($stmt->rowCount() > 0){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "
              <a class='card type-found' href='post_detail.php?id={$row['id']}&type=found'>
                <img src='uploads/{$row['image']}' alt='{$row['item_name']}'>
                <div class='card-content'>
                  <h3><i class='fas fa-box'></i> {$row['item_name']}</h3>
                  <p><strong>วันที่พบ:</strong> {$row['found_date']}</p>
                  <p><strong>สถานที่:</strong> {$row['found_place']}</p>
                </div>
              </a>
              ";
            }
          } else {
            echo "<p style='color:#999;'>ยังไม่มีโพสต์ของที่เจอ</p>";
          }

        }catch(PDOException $e){
          echo "<p style='color:red;'>เกิดข้อผิดพลาด: ".$e->getMessage()."</p>";
        }
      ?>
      </div>
    </div>

  </div> <!-- end columns -->

</div>
</body>
</html>

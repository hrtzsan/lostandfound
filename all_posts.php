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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { 
    font-family: 'Poppins', sans-serif; 
    margin:0; 
    padding:0; 
    background: #000; 
    color: #f5f5f7;
}
h1 { 
    text-align:center; 
    margin:30px 0; 
    font-weight: 600; 
}

/* Container */
.container { max-width:1200px; margin:auto; padding:20px; }

/* Back Link */
.back-link { 
    display:inline-flex; 
    align-items:center; 
    gap: 8px; 
    margin-top: 20px; 
    margin-bottom: 10px;
    color: #0071e3; 
    font-weight:500; 
    text-decoration:none; 
    transition: color 0.2s; 
}
.back-link:hover { color: #147ce5; }

/* Search Form */
.search-form {
    display:flex;
    justify-content:center;
    gap:15px;
    flex-wrap:wrap;
    margin: 30px auto 40px auto;
    max-width: 900px;
}
.search-form input, .search-form select {
    padding:12px 15px;
    font-size:16px;
    background-color: #1c1c1e;
    border:1px solid #38383a;
    border-radius:10px;
    color: #f5f5f7;
    font-family: 'Poppins', sans-serif;
}
.search-form input { flex:3; min-width:250px; }
.search-form select { flex:1; min-width:150px; }
.search-form button {
    padding:12px 25px;
    font-size:16px;
    border:none;
    border-radius:10px;
    background: #0071e3;
    color:white;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap: 8px;
    transition: background-color 0.2s;
}
.search-form button:hover { background: #147ce5; }

/* Layout */
.columns { display:flex; flex-wrap:wrap; gap:40px; }
.column { flex:1; min-width:300px; }
/* When a column is the only one shown, it takes full width */
.column.full-width {
    flex-basis: 100%;
}

/* Grid */
.grid { 
    display:grid; 
    grid-template-columns:repeat(auto-fill, minmax(250px, 1fr)); 
    gap:20px; 
}
.column.full-width .grid {
    grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));
}

/* Card */
.card { 
    background: #1c1c1e; 
    border-radius:12px; 
    overflow:hidden; 
    border: 1px solid #38383a;
    text-decoration:none; 
    color:inherit; 
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover { 
    transform: translateY(-5px); 
    box-shadow:0 10px 25px rgba(0,0,0,0.4); 
}
.card img { 
    width:100%; 
    height:180px; 
    object-fit:cover; 
    display:block; 
}
.card-content { padding:15px; }
.card-content h3 { 
    margin:0 0 8px 0; 
    font-size:16px; 
    display:flex; 
    align-items:center; 
    gap:8px; 
    white-space:nowrap; 
    overflow:hidden; 
    text-overflow:ellipsis; 
}
.card-content p { 
    margin:3px 0; 
    font-size:13px; 
    color: #a1a1a6; 
}
.card-content p strong { color: #e0e0e0; }
.type-lost h3 { color:#ff453a; } /* Apple Red */
.type-found h3 { color:#32d74b; } /* Apple Green */

/* Section Title */
.section-title { 
    margin-bottom:20px; 
    font-size:24px; 
    font-weight:600; 
    display:flex; 
    align-items:center; 
    gap:10px; 
    color: #f5f5f7;
    border-bottom: 1px solid #38383a;
    padding-bottom: 15px;
}
</style>
</head>
<body>

<div class="container">

  <a class="back-link" href="index.php">
    <i class="fas fa-arrow-left"></i> กลับหน้าแรก
  </a>

  <h1>โพสต์ทั้งหมด</h1>

  <form class="search-form" method="get" action="all_posts.php">
    <input type="text" name="q" placeholder="ค้นหาด้วยชื่อสิ่งของ..." value="<?php echo htmlspecialchars($search); ?>">
    <select name="type">
      <option value="all" <?php if($type=='all') echo 'selected'; ?>>โพสต์ทั้งหมด</option>
      <option value="lost" <?php if($type=='lost') echo 'selected'; ?>>ของหาย</option>
      <option value="found" <?php if($type=='found') echo 'selected'; ?>>พบของ</option>
    </select>
    <button type="submit">
      <i class="fas fa-search"></i> ค้นหา
    </button>
  </form>

  <div class="columns">

    <?php if ($type === 'all' || $type === 'lost'): ?>
    <div class="column <?php if($type === 'lost') echo 'full-width'; ?>">
      <div class="section-title"><i class="fas fa-thumbtack" style="color: #ff453a;"></i> ของหาย</div>
      <div class="grid">
      <?php
        try{
          $sql = "SELECT * FROM lost_items";
          $params = [];
          if($search){
            $sql .= " WHERE item_name LIKE :search";
            $params['search'] = "%$search%";
          }
          $sql .= " ORDER BY lost_date DESC";
          
          $stmt = $pdo->prepare($sql);
          $stmt->execute($params);

          if($stmt->rowCount() > 0){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "
              <a class='card type-lost' href='post_detail.php?id={$row['id']}&type=lost'>
                <img src='uploads/".htmlspecialchars($row['image'])."' alt='".htmlspecialchars($row['item_name'])."' onerror=\"this.src='assets/placeholder.png'\">
                <div class='card-content'>
                  <h3><i class='fas fa-thumbtack'></i> ".htmlspecialchars($row['item_name'])."</h3>
                  <p><strong>วันที่หาย:</strong> {$row['lost_date']}</p>
                  <p><strong>สถานที่:</strong> ".htmlspecialchars($row['lost_place'])."</p>
                </div>
              </a>";
            }
          } else {
            echo "<p style='color: #999; grid-column: 1 / -1;'>ไม่พบโพสต์ของหายที่ตรงกับการค้นหา</p>";
          }
        }catch(PDOException $e){
          echo "<p style='color:red;'>เกิดข้อผิดพลาด: ".$e->getMessage()."</p>";
        }
      ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($type === 'all' || $type === 'found'): ?>
    <div class="column <?php if($type === 'found') echo 'full-width'; ?>">
      <div class="section-title"><i class="fas fa-box" style="color: #32d74b;"></i> ของที่เจอ</div>
      <div class="grid">
      <?php
        try{
            $sql = "SELECT * FROM found_items";
            $params = [];
            if($search){
              $sql .= " WHERE item_name LIKE :search";
              $params['search'] = "%$search%";
            }
            $sql .= " ORDER BY found_date DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

          if($stmt->rowCount() > 0){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "
              <a class='card type-found' href='post_detail.php?id={$row['id']}&type=found'>
                <img src='uploads/".htmlspecialchars($row['image'])."' alt='".htmlspecialchars($row['item_name'])."' onerror=\"this.src='assets/placeholder.png'\">
                <div class='card-content'>
                  <h3><i class='fas fa-box'></i> ".htmlspecialchars($row['item_name'])."</h3>
                  <p><strong>วันที่พบ:</strong> {$row['found_date']}</p>
                  <p><strong>สถานที่:</strong> ".htmlspecialchars($row['found_place'])."</p>
                </div>
              </a>";
            }
          } else {
            echo "<p style='color:#999; grid-column: 1 / -1;'>ไม่พบโพสต์ของที่เจอที่ตรงกับการค้นหา</p>";
          }
        }catch(PDOException $e){
          echo "<p style='color:red;'>เกิดข้อผิดพลาด: ".$e->getMessage()."</p>";
        }
      ?>
      </div>
    </div>
    <?php endif; ?>

  </div> </div>
</body>
</html>
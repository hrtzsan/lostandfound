<?php include "db.php";
session_start();
?>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Lost & Found</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@400;500;700&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <style>
    html {
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      overflow-x: hidden;
    }

    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 12px 20px;
      z-index: 10;
    }

    .nav-links {
      display: flex;
      gap: 20px;
      margin: 0 auto;
    }

    .nav-links a {
      text-decoration: none;
      color: #ffffff;
      padding: 8px 15px;
      border-radius: 20px;
      transition: 0.3s;
      position: relative;
    }

    .nav-links a::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 0;
      height: 2px;
      background: #fff;
      transition: width 0.3s;
    }

    .nav-links a:hover::after,
    .nav-links a.active::after {
      width: 100%;
    }

    .slider {
      position: relative;
      width: 100%;
      height: 100vh;
      display: flex;
      overflow: hidden;
      background-color: #000000;
    }

    .slides {
      display: flex;
      width: 300%;
      height: 100%;
      transition: transform 0.7s ease-in-out;
    }

    .slide {
      width: 100%;
      height: 70%;
      flex-shrink: 0;
      background-size: cover;
      background-position: center;
    }

    .slide1 { background-image: url('assets/landf1.png'); }
    .slide2 { background-image: url('assets/landf2.png'); }
    .slide3 { background-image: url('assets/landf3.png'); }

    .nav-btn {
      position: absolute;
      top: 37%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0);
      color: #fff;
      font-size: 30px;
      padding: 15px;
      cursor: pointer;
      border-radius: 50%;
      z-index: 5;
      border: none;
      outline: none;
      transition: background 0.5s, transform 0.5s;
    }

    .nav-btn:hover {
      background: rgba(0, 0, 0, 0);
      transform: translateY(-50%) scale(1.1);
    }

    .prev { left: 10px; }
    .next { right: 10px; }

    .dots {
      position: absolute;
      bottom: 250px;
      left: 50%;
      transform: translateX(-50%);
      display: inline-block;
      padding: 5px 10px;
      background: rgba(0, 0, 0, 0);
      border-radius: 20px;
    }

    .dots span {
      display: inline-block;
      width: 8px;
      height: 8px;
      margin: 0 4px;
      background: rgba(255, 255, 255, 0.397);
      border-radius: 50%;
      cursor: pointer;
    }

    .dots span.active { background: #ffffff8a; }

    .content {
      position: absolute;
      top: 38%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      color: #fff;
      z-index: 5;
    }

    .content h1 {
      font-family: 'Playfair Display', serif;
      font-size: 80px;
      margin: 0px;
    }

    .content p {
      font-family: 'Itim', cursive;
      font-size: 30px;
      margin: 0px 0 0 0;
      color: #642828; 
    }

  section {
    width: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: left;
    padding: 20px;
    box-sizing: border-box;
    background-color: #727272ff;
  }

    /* Form */
    .form-container {
      max-width: 600px; margin: 0 auto;
      background: rgb(255, 255, 255); padding: 30px;
      border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-container h2 { text-align: center; margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"], input[type="file"], input[type="date"], select, textarea {
      width: 100%; padding: 10px;
      border: 1px solid #ddd; border-radius: 5px;
      box-sizing: border-box;
    }
    textarea { min-height: 100px; }
    input[type="submit"] {
      background-color: #4CAF50; color: white;
      padding: 12px 20px; border: none;
      border-radius: 5px; cursor: pointer;
      width: 100%; font-size: 16px;
    }
    input[type="submit"]:hover { background-color: #45a049; }
    .required { color: red; }

        /* Section */
    section {
      width: 100%;
      padding: 50px 20px;
      box-sizing: border-box;
    }

    /* Search */
    #search form {
      display: flex;
      justify-content: center;
      gap: 10px;
      max-width: 700px;
      margin: 0 auto;
    }
    #search input[type="text"] {
      flex: 1;
      padding: 12px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    #search button {
      padding: 12px 20px;
      font-size: 16px;
      background-color: #ff0000;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    /* Posts grid */
    .posts-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }
    .post-card {
      text-align: center;
    }
    .post-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
    }
    .post-card h4 { margin: 10px 0 5px; font-size: 18px; color:#333; }
    .post-card p { margin: 2px 0; font-size: 14px; color:#555; }

    /* Footer */
    footer { padding: 20px; text-align:center; background:#333; color:#fff; margin-top:auto; }
  </style>
</head>
<body>

<nav class="navbar" style="
    position: fixed;        /* fixed ตลอดเวลา */
    top: 0;                 /* ติดบนสุด */
    left: 0;
    width: 100%;            /* กว้างเต็ม */
    height: 60px;
    display: flex;
    align-items: center;
    color: white;
    background: rgba(255, 255, 255, 0);  /* โปร่งใสเต็ม */
    padding: 12px 20px;
    z-index: 1000;          /* อยู่เหนือเนื้อหาอื่น */
">

  <!-- เมนูหลักตรงกลาง -->
  <div class="nav-links" style="
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 20px;
  ">
    <a href="#home" class="active">หน้าแรก</a>
    <a href="#about">เกี่ยวกับเรา</a>
    <a href="#guide">คำแนะนำ</a>
    <a href="#search">ค้นหา</a>
    <a href="#lost-found">โพสต์</a>
  </div>

  <!-- ปุ่มเข้าสู่ระบบ / ชื่อผู้ใช้ ด้านขวา -->
  <div style="
      margin-left: auto;
      display: flex;
      gap: 15px;
      align-items: center;
  ">
    <?php if(isset($_SESSION['username'])): ?>
        <span>สวัสดี, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
        <a href="logout.php" style="
            color: white;
            text-decoration: none;
            padding: 6px 12px;
            border: 1px solid white;
            border-radius: 20px;
            transition: 0.3s;
        ">ออกจากระบบ</a>
    <?php else: ?>
        <a href="login.php" style="
            color: white;
            text-decoration: none;
            padding: 6px 12px;
            border: 1px solid white;
            border-radius: 20px;
            transition: 0.3s;
        ">เข้าสู่ระบบ</a>
        <a href="signup.php" style="
            color: white;
            text-decoration: none;
            padding: 6px 12px;
            border: 1px solid white;
            border-radius: 20px;
            transition: 0.3s;
            margin-right: 35px;
        ">สมัครสมาชิก</a>
    <?php endif; ?>
  </div>
</nav>
  
  <!-- Hero / Slider -->
  <div class="slider" id="home">
    <div class="slides" id="slides">
      <div class="slide slide1"></div>
      <div class="slide slide2"></div>
      <div class="slide slide3"></div>
    </div>
    <button class="nav-btn prev" onclick="moveSlide(-1)">&#10094;</button>
    <button class="nav-btn next" onclick="moveSlide(1)">&#10095;</button>
    <div class="dots" id="dots">
      <span onclick="currentSlide(0)" class="active"></span>
      <span onclick="currentSlide(1)"></span>
      <span onclick="currentSlide(2)"></span>
    </div>
    <div class="content">
      <h1>Lost & Found</h1>
      <p>ของหายได้คืน</p>
    </div>
  </div>

<section id="about" style="padding:80px 20px; background:#f7f7f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#333;">
  <div style="max-width:1200px; margin:0 auto; text-align:center;">
    <h2 style="font-size:48px; margin-bottom:10px; font-weight:600;">เกี่ยวกับเรา</h2>
    <p style="font-size:18px; margin-bottom:50px; line-height:1.6; color:#555;">
      เว็บไซต์ <strong>Lost & Found</strong> ช่วยให้ผู้ที่ทำของหายหรือพบเจอของสามารถติดต่อกันได้สะดวกและปลอดภัย
    </p>

    <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:40px;">
      
      <!-- Card 1: Magnifying Glass -->
      <div class="about-card" style="transition-delay:0s;">
        <i class="fa-solid fa-magnifying-glass" style="font-size:60px; color:#0071e3; margin-bottom:20px;"></i>
        <h3 style="font-size:22px; font-weight:600; margin-bottom:10px;">ค้นหา</h3>
        <p style="font-size:16px; line-height:1.5; color:#555;">ค้นหาของหายหรือพบเจอได้ง่ายและรวดเร็ว</p>
      </div>

      <!-- Card 2: Paperclip -->
      <div class="about-card" style="transition-delay:0.2s;">
        <i class="fa-solid fa-paperclip" style="font-size:60px; color:#0071e3; margin-bottom:20px;"></i>
        <h3 style="font-size:22px; font-weight:600; margin-bottom:10px;">โพสต์หาของหาย</h3>
        <p style="font-size:16px; line-height:1.5; color:#555;">กรอกข้อมูลของสิ่งของที่หายพร้อมรายละเอียดและช่องทางติดต่อ</p>
      </div>

      <!-- Card 3: Pin -->
      <div class="about-card" style="transition-delay:0.4s;">
        <i class="fa-solid fa-thumbtack" style="font-size:60px; color:#0071e3; margin-bottom:20px;"></i>
        <h3 style="font-size:22px; font-weight:600; margin-bottom:10px;">แจ้งของที่พบเจอ</h3>
        <p style="font-size:16px; line-height:1.5; color:#555;">โพสต์สิ่งของที่คุณเก็บได้เพื่อช่วยคืนเจ้าของได้อย่างสะดวกและปลอดภัย</p>
      </div>

    </div>
  </div>
</section>

<style>
  .about-card {
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    padding:40px; 
    border-radius:30px; 
    width:250px; 
    box-shadow:0 15px 40px rgba(0,0,0,0.08); 
    text-align:center; 
    margin-bottom:20px;
    cursor:default; 
    pointer-events:none; 
    opacity:0;
    transform: translateY(50px);
    transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
  }

  .about-card.show {
    opacity:1;
    transform: translateY(0);
  }

  .about-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.12);
  }

  @media (max-width:992px) { .about-card { width:45%; } }
  @media (max-width:600px) { .about-card { width:90%; } }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.about-card');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if(entry.isIntersecting){
          entry.target.classList.add('show');
        }
      });
    }, { threshold: 0.2 });

    cards.forEach(card => observer.observe(card));
  });
</script>

<!-- Instructions Section -->
<section id="guide" style="padding:50px 20px; background:#f9f9f9;">
  <div style="max-width:1200px; margin:auto; text-align:center;">
    <h2 style="margin-bottom:20px; color:#333;">คำแนะนำในการใช้งานเว็บไซต์</h2>
    <p style="font-size:16px; color:#555; margin-bottom:40px;">
      เว็บไซต์นี้ถูกสร้างขึ้นเพื่อช่วยให้ผู้ใช้สามารถแจ้งของหายและแจ้งพบของหายได้อย่างสะดวก  
      กรุณาอ่านวิธีการใช้งานดังต่อไปนี้
    </p>
    
    <!-- Grid 2 แถว 3 คอลัมน์ -->
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:25px; text-align:left;">
      
      <div style="background:white; padding:25px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom:10px; color:#ff0000;">
          <i class="fas fa-search"></i> ค้นหาของหาย/ของเจอ
        </h3>
        <p style="color:#555;">ใช้ช่องค้นหาที่ด้านบน กรอกชื่อสิ่งของหรือสถานที่เพื่อค้นหาข้อมูลที่ตรงกับความต้องการ</p>
      </div>
      
      <div style="background:white; padding:25px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom:10px; color:#ff0000;">
          <i class="fas fa-thumbtack"></i> โพสต์แจ้งของหาย
        </h3>
        <p style="color:#555;">กรอกข้อมูลสิ่งของที่หาย เช่น ชื่อ รายละเอียด วันที่ และสถานที่หาย พร้อมอัปโหลดรูปภาพ</p>
      </div>
      
      <div style="background:white; padding:25px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom:10px; color:#ff0000;">
          <i class="fas fa-box"></i> โพสต์แจ้งของที่เจอ
        </h3>
        <p style="color:#555;">หากคุณพบของหาย สามารถโพสต์รายละเอียด วันที่ และสถานที่พบได้</p>
      </div>
      
      <div style="background:white; padding:25px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom:10px; color:#ff0000;">
          <i class="fas fa-phone"></i> ติดต่อเจ้าของ
        </h3>
        <p style="color:#555;">หากพบโพสต์ที่ตรงกับสิ่งของของคุณ ให้ใช้ข้อมูลติดต่อที่เจ้าของหรือผู้พบได้ระบุไว้</p>
      </div>
      
      <div style="background:white; padding:25px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom:10px; color:#ff0000;">
          <i class="fas fa-exclamation-triangle"></i> มารยาทในการใช้งาน
        </h3>
        <p style="color:#555;">กรุณาโพสต์ข้อมูลที่ถูกต้องและสุภาพ ห้ามลงข้อมูลเท็จ หรือใช้คำไม่เหมาะสม</p>
      </div>
      
      <div style="background:white; padding:25px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom:10px; color:#ff0000;">
          <i class="fas fa-lock"></i> ความปลอดภัย
        </h3>
        <p style="color:#555;">อย่าโพสต์ข้อมูลส่วนตัวที่อ่อนไหว เช่น เลขบัตรประชาชน หรือรหัสส่วนตัว ใช้เฉพาะข้อมูลการติดต่อที่จำเป็น</p>
      </div>
      
    </div>
  </div>
</section>

<section id="search" style="background: #eee; padding: 40px 10px; min-height:auto;">
  <form action="all_posts.php" method="get" style="display:flex; gap:10px; max-width:900px; margin:30px auto 0 auto; width:100%; flex-wrap:wrap; align-items:center;">
    
    <!-- Search input -->
    <input type="text" name="q" placeholder="ค้นหาของหาย / ของที่เจอ..." 
           style="flex:2; padding:12px 15px; font-size:16px; border:1px solid #ccc; border-radius:5px; min-width:200px;">
    
    <!-- Dropdown เลือกประเภท -->
    <select name="type" style="flex:1; font-size:16px; border:1px solid #ccc; border-radius:5px; height:44px; max-width:130px;">
      <option value="all">โพสต์ทั้งหมด</option>
      <option value="lost">ของหาย</option>
      <option value="found">พบของหาย</option>
    </select>
    
    <!-- Submit button -->
    <button type="submit" style="padding:12px 25px; font-size:16px; background:#ff0000; color:white; border:none; border-radius:5px; cursor:pointer; height:44px;">
      ค้นหา
    </button>
    
  </form>
</section>

<section id="search" style="padding: 30px; background: #3d3d3d; display:flex; justify-content:center; gap:40px; flex-wrap:wrap; position:relative;">

  <!-- Lost Items -->
  <div style="flex:1; min-width:400px;">
    <h3 style="margin-bottom:20px; text-align:center; color:#fff;">โพสต์หาของหาย</h3>

    <div class="grid-posts">
      <?php
      include 'db.php';
      try {
          $lost_stmt = $pdo->query("SELECT * FROM lost_items ORDER BY lost_date DESC LIMIT 3");
          if ($lost_stmt->rowCount() > 0) {
              while ($row = $lost_stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "
                  <a href='post_detail.php?id={$row['id']}&type=lost' class='post-link'>
                    <div class='post-card'>
                      <img src='uploads/{$row['image']}' alt='{$row['item_name']}'>
                      <div class='post-info'>
                        <h4>{$row['item_name']}</h4>
                        <p>วันที่หาย: {$row['lost_date']}</p>
                        <p>สถานที่: {$row['lost_place']}</p>
                      </div>
                    </div>
                  </a>
                  ";
              }
          } else {
              echo "<p style='color:#ccc; text-align:center;'>ยังไม่มีข้อมูล</p>";
          }
      } catch (PDOException $e) {
          echo "<p style='color:red; text-align:center;'>เกิดข้อผิดพลาด: ".$e->getMessage()."</p>";
      }
      ?>
    </div>
  </div>

  <!-- Found Items -->
  <div style="flex:1; min-width:400px;">
    <h3 style="margin-bottom:20px; text-align:center; color:#fff;">โพสต์พบของหาย</h3>

    <div class="grid-posts">
      <?php
      try {
          $found_stmt = $pdo->query("SELECT * FROM found_items ORDER BY found_date DESC LIMIT 3");
          if ($found_stmt->rowCount() > 0) {
              while ($row = $found_stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "
                  <a href='post_detail.php?id={$row['id']}&type=found' class='post-link'>
                    <div class='post-card'>
                      <img src='uploads/{$row['image']}' alt='{$row['item_name']}'>
                      <div class='post-info'>
                        <h4>{$row['item_name']}</h4>
                        <p>วันที่พบ: {$row['found_date']}</p>
                        <p>สถานที่: {$row['found_place']}</p>
                      </div>
                    </div>
                  </a>
                  ";
              }
          } else {
              echo "<p style='color:#ccc; text-align:center;'>ยังไม่มีข้อมูล</p>";
          }
      } catch (PDOException $e) {
          echo "<p style='color:red; text-align:center;'>เกิดข้อผิดพลาด: ".$e->getMessage()."</p>";
      }
      ?>
    </div>
  </div>

<!-- ปุ่มดูโพสต์ทั้งหมดตรงกลางใต้ grid-posts -->
<div style="width:100%; text-align:center; margin-top:-250px;">
    <a href="all_posts.php" class="btn-all-posts">ดูโพสต์ทั้งหมด</a>
</div>
</section>

<style>
.grid-posts {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* fix 3 columns */
    gap: 20px;
    margin-bottom: 30px;
}

.post-link { 
    text-decoration: none; 
    color: inherit; 
}

.post-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.post-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.post-info {
    padding: 12px;
}
.post-info h4 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: #333;
}
.post-info p {
    margin: 3px 0;
    font-size: 13px;
    color: #555;
}

/* ปุ่มดูโพสต์ทั้งหมด */
.btn-all-posts {
    padding: 12px 30px;
    background: #ff0000; /* สีแดง */
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    display: inline-block;
    transition: 0.2s;
}
.btn-all-posts:hover {
    background: #e60000; /* สีเข้มขึ้นเมื่อ hover */
}
</style>

<section id="lost-found">
  <div style="display: flex; gap: 20px; max-width: 1200px; margin: 50px auto; flex-wrap: wrap;">
    
    <!-- โพสต์ของหาย -->
    <div style="flex: 1; min-width: 300px; color: #000000">
      <div class="form-container">
        <h2>โพสต์แจ้งของหาย</h2>
        <form action="lost_create.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="lost_title">ชื่อสิ่งของที่หาย <span class="required">*</span></label>
            <input type="text" id="lost_title" name="lost_title" placeholder="เช่น กระเป๋าสตางค์" required>
          </div>
          <div class="form-group">
            <label for="lost_description">รายละเอียด</label>
            <textarea id="lost_description" name="lost_description" placeholder="รายละเอียดเพิ่มเติม เช่น สี, ยี่ห้อ" required></textarea>
          </div>
          <div class="form-group">
            <label for="lostDate">วันที่หาย <span class="required">*</span></label>
            <input type="date" id="lostDate" name="lostDate" required>
          </div>
          <div class="form-group">
            <label for="lost_location">สถานที่ที่หาย <span class="required">*</span></label>
            <input type="text" id="lost_location" name="lost_location" placeholder="เช่น อาคาร A ชั้น 2">
          </div>
          <div class="form-group">
            <label for="lost_contact">ข้อมูลติดต่อ <span class="required">*</span></label>
            <input type="text" id="lost_contact" name="lost_contact" placeholder="เช่น เบอร์โทร หรือ LINE ID" required>
          </div>
          <div class="form-group">
            <label for="lost_status">สถานะ</label>
            <select id="lost_status" name="lost_status">
              <option value="lost">ยังหาไม่เจอ</option>
              <option value="found">เจอแล้ว</option>
            </select>
          </div>
          <div class="form-group">
            <label for="lost_item_image">รูปภาพสิ่งของ</label>
            <input type="file" id="lost_item_image" name="lost_item_image" accept="image/*">
            <small>รองรับ JPG, PNG, GIF (ไม่เกิน 2MB)</small>
          </div>
          <input type="submit" value="โพสต์">
        </form>
      </div>
    </div>

    <!-- โพสต์ของที่พบ -->
    <div style="flex: 1; min-width: 300px; color: #000000">
      <div class="form-container">
        <h2>โพสต์แจ้งพบของหาย</h2>
        <form action="found_create.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="found_title">ชื่อสิ่งของที่พบเจอ <span class="required">*</span></label>
            <input type="text" id="found_title" name="found_title" placeholder="เช่น กระเป๋าสตางค์" required>
          </div>
          <div class="form-group">
            <label for="found_description">รายละเอียด</label>
            <textarea id="found_description" name="found_description" placeholder="รายละเอียดเพิ่มเติม เช่น สี, ยี่ห้อ" required></textarea>
          </div>
          <div class="form-group">
            <label for="foundDate">วันที่พบ <span class="required">*</span></label>
            <input type="date" id="foundDate" name="foundDate" required>
          </div>
          <div class="form-group">
            <label for="found_location">สถานที่ที่พบ <span class="required">*</span></label>
            <input type="text" id="found_location" name="found_location" placeholder="เช่น อาคาร A ชั้น 2">
          </div>
          <div class="form-group">
            <label for="found_contact">ข้อมูลติดต่อ <span class="required">*</span></label>
            <input type="text" id="found_contact" name="found_contact" placeholder="เช่น เบอร์โทร หรือ LINE ID" required>
          </div>
          <div class="form-group">
            <label for="found_status">สถานะ</label>
            <select id="found_status" name="found_status">
              <option value="lost">ยังหาเจ้าของไม่เจอ</option>
              <option value="found">เจอเจ้าของแล้ว</option>
            </select>
          </div>
          <div class="form-group">
            <label for="found_item_image">รูปภาพสิ่งของ</label>
            <input type="file" id="found_item_image" name="found_item_image" accept="image/*">
            <small>รองรับ JPG, PNG, GIF (ไม่เกิน 2MB)</small>
          </div>
          <input type="submit" value="โพสต์">
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Footer Contact Section -->
<footer style="background: linear-gradient(135deg, #ff4d4d, #ff0000); color:white; padding:60px 20px;">
  <div style="max-width:1200px; margin:auto; display:flex; flex-wrap:wrap; justify-content:space-between; gap:30px;">

    <!-- About -->
    <div style="flex:1; min-width:250px;">
      <h3 style="margin-bottom:15px; font-size:22px; font-weight:bold;">Lost & Found</h3>
      <p style="line-height:1.8; color:#ffe5e5;">
        เว็บไซต์สำหรับแจ้งของหายและของที่พบเจอ  
        ช่วยให้เจ้าของและผู้พบเจอสามารถติดต่อกันได้ง่ายและรวดเร็ว
      </p>
      <p style="margin-top:15px; font-size:14px; color:#ffd6d6;">
        ติดตามเรา: 
        <a href="#" style="color:white; margin:0 5px; text-decoration:none;"><i class="fab fa-facebook-f"></i> Facebook</a> | 
        <a href="#" style="color:white; margin:0 5px; text-decoration:none;"><i class="fab fa-instagram"></i> Instagram</a>
      </p>
    </div>

    <!-- Contact Info -->
    <div style="flex:1; min-width:250px;">
      <h3 style="margin-bottom:15px; font-size:20px; font-weight:bold;">ติดต่อเรา</h3>
      <p style="margin:8px 0; font-size:16px;"><i class="fas fa-map-marker-alt"></i> มหาวิทยาลัยราชภัฏเลย</p>
      <p style="margin:8px 0; font-size:16px;"><i class="fas fa-phone"></i> 012-345-6789</p>
      <p style="margin:8px 0; font-size:16px;"><i class="fas fa-envelope"></i> support@lostandfound.com</p>
    </div>

    <!-- Quick Links -->
    <div style="flex:1; min-width:200px;">
      <h3 style="margin-bottom:15px; font-size:20px; font-weight:bold;">ลิงก์ด่วน</h3>
      <ul style="list-style:none; padding:0; margin:0; line-height:2;">
        <li><a href="#search" style="color:white; text-decoration:none;"><i class="fas fa-search"></i> ค้นหา</a></li>
        <li><a href="#lost-found" style="color:white; text-decoration:none;"><i class="fas fa-plus-circle"></i> แจ้งของหาย</a></li>
        <li><a href="#lost-found" style="color:white; text-decoration:none;"><i class="fas fa-plus-circle"></i> แจ้งเจอของ</a></li>
        <li><a href="#guide" style="color:white; text-decoration:none;"><i class="fas fa-book"></i> วิธีใช้งาน</a></li>
      </ul>
    </div>

  </div>

  <!-- Bottom Bar -->
  <div style="margin-top:40px; border-top:1px solid rgba(255,255,255,0.3); padding-top:20px; text-align:center;">
    <p style="color:rgba(255,255,255,0.7); font-size:14px; margin:0;">
      © 2025 Lost & Found | All rights reserved.
    </p>
  </div>
</footer>

  <script>
    let currentIndex = 0;
    const slides = document.getElementById("slides");
    const dots = document.querySelectorAll("#dots span");

    function showSlide(index) {
      if (index >= dots.length) index = 0;
      if (index < 0) index = dots.length - 1;
      currentIndex = index;
      slides.style.transform = `translateX(-${index * 100}%)`;
      dots.forEach(dot => dot.classList.remove("active"));
      dots[index].classList.add("active");
    }
    function moveSlide(step) { showSlide(currentIndex + step); }
    function currentSlide(index) { showSlide(index); }
    setInterval(() => { moveSlide(1); }, 5000);

    const navLinks = document.querySelectorAll(".nav-links a");
    window.addEventListener("scroll", () => {
      let fromTop = window.scrollY + 80;
      navLinks.forEach(link => {
        let section = document.querySelector(link.getAttribute("href"));
        if (section &&
            section.offsetTop <= fromTop &&
            section.offsetTop + section.offsetHeight > fromTop) {
          navLinks.forEach(l => l.classList.remove("active"));
          link.classList.add("active");
        }
      });
    });
  </script>
</body>
</html>
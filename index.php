<?php 
include "db.php";
session_start();
?>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Lost & Found</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Itim&family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* --- 1. Global & Base Styles --- */
    :root {
      --bg-color: #000000;
      --surface-color: #1c1c1e;
      --border-color: #38383a;
      --primary-text: #f5f5f7;
      --secondary-text: #a1a1a6;
      --accent-blue: #0071e3;
      --accent-blue-hover: #147ce5;
      --accent-red: #ff453a;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      background-color: var(--bg-color);
      color: var(--primary-text);
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      overflow-x: hidden;
    }

    section {
      width: 100%;
      padding: 100px 20px;
      box-sizing: border-box;
      position: relative;
    }

    h1, h2, h3, h4 {
        font-weight: 600;
    }
    
    a {
        text-decoration: none;
        color: var(--accent-blue);
    }


    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        display: flex;
        align-items: center;
        padding: 12px 20px;
        z-index: 1000;
        background: transparent;
        height: 60px;
    }
    
    .nav-links-wrapper {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .nav-links {
      display: flex;
      gap: 20px;
    }

    .nav-links a {
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

    .nav-auth {
    margin-left: auto;      /* ดันกล่องข้อความไปทางขวาสุด */
    display: flex;         /* เปิดใช้งาน Flexbox */
    align-items: center;   /* จัดให้ทุกอย่างในกล่องอยู่กึ่งกลางแนวตั้ง (สำคัญที่สุด) */
    gap: 15px;             /* กำหนดระยะห่างระหว่างข้อความและปุ่ม */
}

.nav-auth span { 
    font-size: 14px; 
    color: #ffffff; 
}

/* สไตล์สำหรับปุ่มทั้งหมด (Login, Signup, Logout) */
.nav-auth a {
    color: white;
    text-decoration: none;
    padding: 6px 12px;
    border: 1px solid white;
    border-radius: 20px;
    transition: background-color 0.3s;
    font-size: 14px;
    white-space: nowrap; /* ป้องกันปุ่มตกบรรทัด */
}

.nav-auth a:hover { 
    background-color: rgba(255,255,255,0.2); 
}

/* เพิ่มระยะห่างขอบขวาสำหรับปุ่มสุดท้าย */
    .nav-auth a[href="signup.php"],
    .nav-auth a[href="logout.php"] {
    margin-right: 35px;
}

/* เพิ่มส่วนนี้เข้าไปใน CSS ของ Navbar */
.nav-auth .nav-profile-link {
    color: #ffffff;
    text-decoration: none;
    padding: 6px 0; /* ปรับ padding ให้ไม่มีขอบ */
    border: none; /* เอากรอบออก */
    transition: color 0.3s;
}
.nav-auth .nav-profile-link:hover {
    color: #a1a1a6; /* สีจางลงเมื่อ hover */
    background-color: transparent; /* ทำให้ไม่มีพื้นหลังตอน hover */
}
    
    .slider {
        position: relative;
        width: 100%;
        height: 100vh;
        display: flex;
        overflow: hidden;
        background-color: #000;
    }

    .slides {
        display: flex;
        width: 300%; /* Original width */
        height: 100%;
        transition: transform 0.7s ease-in-out;
    }

    .slide {
        width: 100%;
        height: 70%; /* Original height */
        flex-shrink: 0;
        background-size: cover;
        background-position: center;
    }
    
    .slide1 { background-image: url('assets/landf1.png'); }
    .slide2 { background-image: url('assets/landf2.png'); }
    .slide3 { background-image: url('assets/landf3.png'); }

    .nav-btn {
        position: absolute;
        top: 37%; /* Original position */
        transform: translateY(-50%);
        background: transparent;
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
        background: transparent;
        transform: translateY(-50%) scale(1.1);
    }
    .prev { left: 10px; }
    .next { right: 10px; }

    .dots {
        position: absolute;
        bottom: 250px; /* Original position */
        left: 50%;
        transform: translateX(-50%);
        background: transparent;
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
        top: 38%; /* Original position */
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #fff;
        z-index: 5;
    }

    .content h1 {
        font-family: 'Playfair Display', serif; /* Original font */
        font-size: 80px; /* Original size */
        margin: 0;
    }

    .content p {
        font-family: 'Itim', cursive; /* Original font */
        font-size: 30px; /* Original size */
        margin: 0;
        color: #642828; /* Original color */
    }

    /* --- 4. Section Styles (About, Guide, etc.) --- */
    .section-container {
      max-width: 1200px;
      margin: 0 auto;
      text-align: center;
    }
    
    .section-title {
        font-size: 48px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .section-subtitle {
        font-size: 18px;
        margin: 0 auto 60px auto;
        line-height: 1.7;
        color: var(--secondary-text);
        max-width: 680px;
    }

    /* About Section */
    .about-cards-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 40px;
    }
    .about-card {
      background: var(--surface-color);
      padding: 40px; 
      border-radius: 20px; 
      border: 1px solid var(--border-color);
      width: 280px;
      text-align: center;
      opacity: 0;
      transform: translateY(50px);
      transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .about-card.show {
      opacity: 1;
      transform: translateY(0);
    }
    .about-card i {
        font-size: 50px;
        color: var(--accent-blue);
        margin-bottom: 20px;
    }
    .about-card h3 {
        font-size: 22px;
        margin-bottom: 10px;
    }
    .about-card p {
        font-size: 16px;
        line-height: 1.6;
        color: var(--secondary-text);
    }

    /* Guide Section */
    .guide-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        text-align: left;
    }
    .guide-card {
        background: var(--surface-color);
        padding: 30px;
        border-radius: 20px;
        border: 1px solid var(--border-color);
    }
    .guide-card h3 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 20px;
        display: flex;
        align-items: center;
    }
    .guide-card i {
        color: var(--accent-blue);
        margin-right: 12px;
        font-size: 20px;
        width: 24px;
        text-align: center;
    }
    .guide-card p {
        color: var(--secondary-text);
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* --- 5. Search & Posts Section --- */
    #search { min-height: auto; padding: 60px 20px;}
    .search-form {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
      max-width: 900px;
      margin: 0 auto;
    }
    #search input[type="text"], #search select {
      padding: 12px 15px;
      font-size: 16px;
      background-color: var(--surface-color);
      border: 1px solid var(--border-color);
      border-radius: 10px;
      color: var(--primary-text);
    }
    #search input[type="text"] {
        flex: 3;
        min-width: 250px;
    }
    #search select {
        flex: 1;
        min-width: 150px;
    }
    #search button {
        padding: 12px 25px;
        font-size: 16px;
        background-color: var(--accent-blue);
        color: #fff;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    #search button:hover {
        background-color: var(--accent-blue-hover);
    }

    /* Posts Grid Section (ID renamed for validity) */
    #recent-posts {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 40px;
    }
    .posts-column {
        flex: 1;
        min-width: 350px;
        max-width: 600px;
    }
    .posts-column h3 {
        margin-bottom: 25px;
        text-align: center;
        font-size: 28px;
    }
    .grid-posts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    .post-link { text-decoration: none; }
    .post-card {
      background: var(--surface-color);
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid var(--border-color);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .post-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    }
    .post-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
    }
    .post-info { padding: 15px; }
    .post-info h4 {
      margin: 0 0 8px 0;
      font-size: 16px;
      color: var(--primary-text);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .post-info p {
      margin: 3px 0;
      font-size: 13px;
      color: var(--secondary-text);
    }
    .btn-all-posts-wrapper {
        width: 100%;
        text-align: center;
        margin-top: 40px;
    }
    .btn-all-posts {
        padding: 12px 30px;
        background: var(--accent-blue);
        color: #fff;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 500;
        transition: background-color 0.3s;
    }
    .btn-all-posts:hover {
        background: var(--accent-blue-hover);
    }

    /* --- 6. Lost & Found Forms Section --- */
    .forms-wrapper {
        display: flex;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        flex-wrap: wrap;
    }
    .form-column {
        flex: 1;
        min-width: 350px;
    }
    .form-container {
        background: var(--surface-color);
        padding: 40px;
        border-radius: 20px;
        border: 1px solid var(--border-color);
    }
    .form-container h2 {
        text-align: center;
        margin-top: 0;
        margin-bottom: 30px;
        font-size: 24px;
    }
    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        color: var(--secondary-text);
        margin-bottom: 8px;
        font-size: 14px;
    }
    .form-group .required {
        color: var(--accent-red);
        margin-left: 2px;
    }
    .form-container input[type="text"],
    .form-container input[type="date"],
    .form-container input[type="file"],
    .form-container textarea,
    .form-container select {
        width: 100%;
        padding: 12px 15px;
        background: #2c2c2e;
        border: 1px solid #444;
        border-radius: 10px;
        color: var(--primary-text);
        font-size: 16px;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-container textarea {
        resize: vertical;
        min-height: 100px;
    }
    .form-container input:focus,
    .form-container textarea:focus,
    .form-container select:focus {
        outline: none;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.3);
    }
    .form-group small {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: #888;
    }
    .form-container input[type="submit"] {
        width: 100%;
        padding: 14px;
        background: var(--accent-blue);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.2s ease;
    }
    .form-container input[type="submit"]:hover {
        background: var(--accent-blue-hover);
    }

    /* --- 7. Footer --- */
    footer {
        background: #111;
        color: var(--primary-text);
        padding: 60px 20px;
        border-top: 1px solid var(--border-color);
    }
    .footer-container {
        max-width: 1200px;
        margin: auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 40px;
    }
    .footer-column {
        flex: 1;
        min-width: 220px;
    }
    .footer-column h3 {
        margin-bottom: 20px;
        font-size: 20px;
    }
    .footer-column p {
        line-height: 1.8;
        color: var(--secondary-text);
        font-size: 15px;
    }
    .footer-column ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .footer-column ul li {
        margin-bottom: 12px;
    }
    .footer-column ul a, .footer-column a {
        color: var(--secondary-text);
        transition: color 0.3s;
    }
    .footer-column ul a:hover, .footer-column a:hover {
        color: var(--primary-text);
    }
    .footer-column .social-links a {
        margin: 0 8px;
    }
    .footer-bottom {
        margin-top: 40px;
        border-top: 1px solid var(--border-color);
        padding-top: 30px;
        text-align: center;
        font-size: 14px;
        color: var(--secondary-text);
    }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="nav-links-wrapper">
    <div class="nav-links">
      <a href="#home" class="active">หน้าแรก</a>
      <a href="#about">เกี่ยวกับเรา</a>
      <a href="#guide">คำแนะนำ</a>
      <a href="#search">ค้นหา</a>
      <a href="#lost-found">โพสต์</a>
    </div>
  </div>
  
  <div class="nav-auth">
    <?php if(isset($_SESSION['username'])): ?>
        <span>สวัสดี, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
        <a href="logout.php">ออกจากระบบ</a>
    <?php else: ?>
        <a href="login.php">เข้าสู่ระบบ</a>
        <a href="signup.php">สมัครสมาชิก</a>
    <?php endif; ?>
  </div>
</nav>
  
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

<section id="about">
  <div class="section-container">
    <h2 class="section-title">เกี่ยวกับเรา</h2>
    <p class="section-subtitle">
      เว็บไซต์ <strong>Lost & Found</strong> ช่วยให้ผู้ที่ทำของหายหรือพบเจอของสามารถติดต่อกันได้สะดวกและปลอดภัย
    </p>
    <div class="about-cards-wrapper">
      <div class="about-card" style="transition-delay:0s;">
        <i class="fa-solid fa-magnifying-glass"></i>
        <h3>ค้นหา</h3>
        <p>ค้นหาของหายหรือพบเจอได้ง่ายและรวดเร็ว</p>
      </div>
      <div class="about-card" style="transition-delay:0.2s;">
        <i class="fa-solid fa-paperclip"></i>
        <h3>โพสต์หาของหาย</h3>
        <p>กรอกข้อมูลของสิ่งของที่หายพร้อมรายละเอียดและช่องทางติดต่อ</p>
      </div>
      <div class="about-card" style="transition-delay:0.4s;">
        <i class="fa-solid fa-thumbtack"></i>
        <h3>แจ้งของที่พบเจอ</h3>
        <p>โพสต์สิ่งของที่คุณเก็บได้เพื่อช่วยคืนเจ้าของได้อย่างสะดวกและปลอดภัย</p>
      </div>
    </div>
  </div>
</section>

<section id="guide">
  <div class="section-container">
    <h2 class="section-title">คำแนะนำในการใช้งาน</h2>
    <p class="section-subtitle">
      เว็บไซต์นี้ถูกสร้างขึ้นเพื่อช่วยให้ผู้ใช้สามารถแจ้งของหายและแจ้งพบของหายได้อย่างสะดวก กรุณาอ่านวิธีการใช้งานดังต่อไปนี้
    </p>
    <div class="guide-grid">
      <div class="guide-card">
        <h3><i class="fas fa-search"></i> ค้นหาของหาย/ของเจอ</h3>
        <p>ใช้ช่องค้นหาที่ด้านบน กรอกชื่อสิ่งของหรือสถานที่เพื่อค้นหาข้อมูลที่ตรงกับความต้องการ</p>
      </div>
      <div class="guide-card">
        <h3><i class="fas fa-thumbtack"></i> โพสต์แจ้งของหาย</h3>
        <p>กรอกข้อมูลสิ่งของที่หาย เช่น ชื่อ รายละเอียด วันที่ และสถานที่หาย พร้อมอัปโหลดรูปภาพ</p>
      </div>
      <div class="guide-card">
        <h3><i class="fas fa-box"></i> โพสต์แจ้งของที่เจอ</h3>
        <p>หากคุณพบของหาย สามารถโพสต์รายละเอียด วันที่ และสถานที่พบได้</p>
      </div>
      <div class="guide-card">
        <h3><i class="fas fa-phone"></i> ติดต่อเจ้าของ</h3>
        <p>หากพบโพสต์ที่ตรงกับสิ่งของของคุณ ให้ใช้ข้อมูลติดต่อที่เจ้าของหรือผู้พบได้ระบุไว้</p>
      </div>
      <div class="guide-card">
        <h3><i class="fas fa-exclamation-triangle"></i> มารยาทในการใช้งาน</h3>
        <p>กรุณาโพสต์ข้อมูลที่ถูกต้องและสุภาพ ห้ามลงข้อมูลเท็จ หรือใช้คำไม่เหมาะสม</p>
      </div>
      <div class="guide-card">
        <h3><i class="fas fa-lock"></i> ความปลอดภัย</h3>
        <p>อย่าโพสต์ข้อมูลส่วนตัวที่อ่อนไหว เช่น เลขบัตรประชาชน หรือรหัสส่วนตัว ใช้เฉพาะข้อมูลการติดต่อที่จำเป็น</p>
      </div>
    </div>
  </div>
</section>

<section id="search">
  <form class="search-form" action="all_posts.php" method="get">
    <input type="text" name="q" placeholder="ค้นหาของหาย / ของที่เจอ...">
    <select name="type">
      <option value="all">โพสต์ทั้งหมด</option>
      <option value="lost">ของหาย</option>
      <option value="found">พบของหาย</option>
    </select>
    <button type="submit">ค้นหา</button>
  </form>
</section>

<section id="recent-posts">
    <div class="posts-column">
        <h3>โพสต์หาของหายล่าสุด</h3>
        <div class="grid-posts">
          <?php
          try {
              $lost_stmt = $pdo->query("SELECT * FROM lost_items ORDER BY lost_date DESC LIMIT 4");
              if ($lost_stmt->rowCount() > 0) {
                  while ($row = $lost_stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo "
                      <a href='post_detail.php?id={$row['id']}&type=lost' class='post-link'>
                        <div class='post-card'>
                          <img src='uploads/{$row['image']}' alt='{$row['item_name']}' onerror=\"this.src='assets/placeholder.png'\">
                          <div class='post-info'>
                            <h4>".htmlspecialchars($row['item_name'])."</h4>
                            <p>วันที่หาย: {$row['lost_date']}</p>
                            <p>สถานที่: ".htmlspecialchars($row['lost_place'])."</p>
                          </div>
                        </div>
                      </a>";
                  }
              } else {
                  echo "<p>ยังไม่มีข้อมูล</p>";
              }
          } catch (PDOException $e) {
              echo "<p style='color:red;'>เกิดข้อผิดพลาด: ".$e->getMessage()."</p>";
          }
          ?>
        </div>
    </div>
    <div class="posts-column">
        <h3>โพสต์พบของล่าสุด</h3>
        <div class="grid-posts">
          <?php
          try {
              $found_stmt = $pdo->query("SELECT * FROM found_items ORDER BY found_date DESC LIMIT 4");
              if ($found_stmt->rowCount() > 0) {
                  while ($row = $found_stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo "
                      <a href='post_detail.php?id={$row['id']}&type=found' class='post-link'>
                        <div class='post-card'>
                          <img src='uploads/{$row['image']}' alt='{$row['item_name']}' onerror=\"this.src='assets/placeholder.png'\">
                          <div class='post-info'>
                            <h4>".htmlspecialchars($row['item_name'])."</h4>
                            <p>วันที่พบ: {$row['found_date']}</p>
                            <p>สถานที่: ".htmlspecialchars($row['found_place'])."</p>
                          </div>
                        </div>
                      </a>";
                  }
              } else {
                  echo "<p>ยังไม่มีข้อมูล</p>";
              }
          } catch (PDOException $e) {
              echo "<p style='color:red;'>เกิดข้อผิดพลาด: ".$e->getMessage()."</p>";
          }
          ?>
        </div>
    </div>
    <div class="btn-all-posts-wrapper">
        <a href="all_posts.php" class="btn-all-posts">ดูโพสต์ทั้งหมด</a>
    </div>
</section>

<style>
  /* --- Styles for the Lost & Found Section --- */
  #lost-found {
    padding: 80px 20px; /* Reduced top/bottom padding */
  }

  /* Reduce the overall width and gap */
  .forms-wrapper {
    display: flex;
    gap: 25px; /* Slightly smaller gap */
    max-width: 1100px; /* Reduced max-width */
    margin: 0 auto;
    flex-wrap: wrap;
  }

  .form-column {
    flex: 1;
    min-width: 320px; /* Adjusted min-width */
  }

  /* --- Styles for the Form Container --- */
  .form-container {
    background: #1c1c1e;
    padding: 30px; /* Reduced padding from 40px */
    border-radius: 20px;
    border: 1px solid #38383a;
  }

  .form-container h2 {
    text-align: center;
    margin-top: 0;
    margin-bottom: 25px; /* Reduced margin from 30px */
    font-size: 22px; /* Reduced font size from 24px */
    font-weight: 600;
    color: #f5f5f7;
  }

  .form-group {
    margin-bottom: 15px; /* Reduced margin from 20px */
  }

  .form-group label {
    display: block;
    color: #a1a1a6;
    margin-bottom: 8px;
    font-size: 14px;
  }

  .form-group .required {
    color: #ff453a;
    margin-left: 2px;
  }

  /* --- Styles for Form Inputs (Made them smaller) --- */
  .form-container input[type="text"],
  .form-container input[type="date"],
  .form-container input[type="file"],
  .form-container textarea,
  .form-container select {
    width: 100%;
    padding: 10px 12px; /* Reduced padding from 12px 15px */
    background: #2c2c2e;
    border: 1px solid #444;
    border-radius: 10px;
    color: #f5f5f7;
    font-size: 15px; /* Reduced font size from 16px */
    box-sizing: border-box;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  
  .form-container textarea {
    resize: vertical;
    min-height: 80px; /* Reduced min-height */
  }

  .form-container input:focus,
  .form-container textarea:focus,
  .form-container select:focus {
    outline: none;
    border-color: #0a84ff;
    box-shadow: 0 0 0 3px rgba(10, 132, 255, 0.3);
  }

  .form-group small {
    display: block;
    margin-top: 6px; /* Reduced margin */
    font-size: 12px;
    color: #888;
  }

  /* --- Styles for Submit Button (Made it smaller) --- */
  .form-container input[type="submit"] {
    width: 100%;
    padding: 12px; /* Reduced padding from 14px */
    background: #0071e3;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 15px; /* Reduced font size */
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.2s ease;
  }

  .form-container input[type="submit"]:hover {
    background: #147ce5;
  }
</style>

<style>
  /* --- Styles for a SHORTER Lost & Found Section --- */
  #lost-found {
    padding: 60px 20px; /* Further reduced top/bottom padding */
  }

  .forms-wrapper {
    display: flex;
    gap: 25px;
    max-width: 1000px; /* Make the whole component narrower */
    margin: 0 auto;
    flex-wrap: wrap;
  }

  .form-column {
    flex: 1;
    min-width: 300px;
  }

  .form-container {
    background: #1c1c1e;
    padding: 25px; /* Further reduced padding */
    border-radius: 15px; /* Less rounded to fit compact look */
    border: 1px solid #38383a;
  }

  .form-container h2 {
    text-align: center;
    margin-top: 0;
    margin-bottom: 20px; /* Reduced margin */
    font-size: 20px; /* Smaller font size */
    font-weight: 600;
    color: #f5f5f7;
  }

  .form-group {
    margin-bottom: 12px; /* Tighter spacing between fields */
  }

  .form-group label {
    display: block;
    color: #a1a1a6;
    margin-bottom: 5px; /* Reduced space below label */
    font-size: 13px; /* Smaller label text */
  }

  .form-group .required {
    color: #ff453a;
  }

  /* --- Styles for Form Inputs (Made them even shorter) --- */
  .form-container input[type="text"],
  .form-container input[type="date"],
  .form-container input[type="file"],
  .form-container textarea,
  .form-container select {
    width: 100%;
    padding: 8px 10px; /* Significantly reduced padding */
    background: #2c2c2e;
    border: 1px solid #444;
    border-radius: 8px;
    color: #f5f5f7;
    font-size: 14px; /* Smaller font in input */
    box-sizing: border-box;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  
  .form-container textarea {
    resize: vertical;
    min-height: 70px; /* Shorter textarea */
  }

  .form-container input:focus,
  .form-container textarea:focus,
  .form-container select:focus {
    outline: none;
    border-color: #0a84ff;
    box-shadow: 0 0 0 2px rgba(10, 132, 255, 0.3);
  }

  .form-group small {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #888;
  }

  /* --- Styles for Submit Button (Made it shorter) --- */
  .form-container input[type="submit"] {
    width: 100%;
    padding: 10px; /* Reduced padding */
    background: #0071e3;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 8px;
    transition: background-color 0.2s ease;
  }

  .form-container input[type="submit"]:hover {
    background: #147ce5;
  }
</style>

<style>
  /* --- Style for the Login Prompt Box --- */
  .login-prompt-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px 20px;
  }

  .login-prompt {
    max-width: 600px;
    width: 100%;
    background: #1c1c1e;
    border: 1px solid #38383a;
    border-radius: 20px;
    padding: 40px;
    text-align: center;
  }

  .login-prompt .icon {
    font-size: 48px;
    color: #0071e3; /* Blue accent color */
    margin-bottom: 20px;
  }

  .login-prompt h2 {
    color: #f5f5f7;
    font-size: 24px;
    font-weight: 600;
    margin-top: 0;
    margin-bottom: 15px;
  }

  .login-prompt p {
    color: #a1a1a6;
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 30px;
  }

  .login-prompt .login-button {
    display: inline-block;
    padding: 12px 30px;
    background: #0071e3;
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    border-radius: 10px;
    text-decoration: none;
    transition: background-color 0.2s ease;
  }

  .login-prompt .login-button:hover {
    background: #147ce5;
  }
</style>

<section id="lost-found">
  
  <?php if (isset($_SESSION['username'])): ?>
    
    <div class="forms-wrapper">
      <div class="form-column">
        <div class="form-container">
          <h2>โพสต์แจ้งของหาย</h2>
          <form action="lost_create.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="lost_title">ชื่อสิ่งของที่หาย <span class="required">*</span></label>
              <input type="text" id="lost_title" name="lost_title" placeholder="เช่น กระเป๋าสตางค์" required>
            </div>
            <div class="form-group">
              <label for="lost_description">รายละเอียด</label>
              <textarea id="lost_description" name="lost_description" placeholder="รายละเอียดเพิ่มเติม เช่น สี, ยี่ห้อ"></textarea>
            </div>
            <div class="form-group">
              <label for="lostDate">วันที่หาย <span class="required">*</span></label>
              <input type="date" id="lostDate" name="lostDate" required>
            </div>
            <div class="form-group">
              <label for="lost_location">สถานที่ที่หาย <span class="required">*</span></label>
              <input type="text" id="lost_location" name="lost_location" placeholder="เช่น อาคาร A ชั้น 2" required>
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
              <small>รองรับ JPG, PNG (ไม่เกิน 2MB)</small>
            </div>
            <input type="submit" value="โพสต์ของหาย">
          </form>
        </div>
      </div>

      <div class="form-column">
        <div class="form-container">
          <h2>โพสต์แจ้งพบของ</h2>
          <form action="found_create.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="found_title">ชื่อสิ่งของที่พบเจอ <span class="required">*</span></label>
              <input type="text" id="found_title" name="found_title" placeholder="เช่น หูฟังไร้สาย" required>
            </div>
            <div class="form-group">
              <label for="found_description">รายละเอียด</label>
              <textarea id="found_description" name="found_description" placeholder="รายละเอียดเพิ่มเติม เช่น สี, ยี่ห้อ"></textarea>
            </div>
            <div class="form-group">
              <label for="foundDate">วันที่พบ <span class="required">*</span></label>
              <input type="date" id="foundDate" name="foundDate" required>
            </div>
            <div class="form-group">
              <label for="found_location">สถานที่ที่พบ <span class="required">*</span></label>
              <input type="text" id="found_location" name="found_location" placeholder="เช่น โรงอาหารกลาง" required>
            </div>
            <div class="form-group">
              <label for="found_contact">ข้อมูลสำหรับติดต่อกลับ</label>
              <input type="text" id="found_contact" name="found_contact" placeholder="เช่น ฝากไว้ที่สำนักงาน" required>
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
              <small>รองรับ JPG, PNG (ไม่เกิน 2MB)</small>
            </div>
            <input type="submit" value="โพสต์ของที่เจอ">
          </form>
        </div>
      </div>
    </div>

  <?php else: ?>

    <div class="login-prompt-wrapper">
      <div class="login-prompt">
        <div class="icon"><i class="fas fa-lock"></i></div>
        <h2>กรุณาเข้าสู่ระบบเพื่อโพสต์</h2>
        <p>คุณต้องเป็นสมาชิกและเข้าสู่ระบบก่อน จึงจะสามารถประกาศของหายหรือแจ้งของที่เจอได้</p>
        <a href="login.php" class="login-button">ไปที่หน้าเข้าสู่ระบบ</a>
      </div>
    </div>

  <?php endif; ?>

</section>

<footer>
  <div class="footer-container">
    <div class="footer-column">
      <h3>Lost & Found</h3>
      <p>
        เว็บไซต์สำหรับแจ้งของหายและของที่พบเจอ ช่วยให้เจ้าของและผู้พบเจอสามารถติดต่อกันได้ง่ายและรวดเร็ว
      </p>
      <p class="social-links">
        ติดตามเรา: 
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </p>
    </div>
    <div class="footer-column">
      <h3>ติดต่อเรา</h3>
        <p><i class="fas fa-map-marker-alt"></i> มหาวิทยาลัยราชภัฏเลย</p>
        <p><i class="fas fa-phone"></i> 012-345-6789</p>
        <p><i class="fas fa-envelope"></i> support@lostandfound.com</p>
    </div>
    <div class="footer-column">
      <h3>ลิงก์ด่วน</h3>
      <ul>
        <li><a href="#search"><i class="fas fa-search"></i> ค้นหา</a></li>
        <li><a href="#lost-found"><i class="fas fa-plus-circle"></i> แจ้งของหาย/เจอของ</a></li>
        <li><a href="#guide"><i class="fas fa-book"></i> วิธีใช้งาน</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 Lost & Found | All rights reserved.</p>
  </div>
</footer>

<script>
  // Script for About Card animation
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

// Script for Slider
let currentIndex = 0;
const slides = document.getElementById("slides");
const dots = document.querySelectorAll("#dots span");
const totalSlides = dots.length;

function showSlide(index) {
  if (index >= totalSlides) index = 0;
  if (index < 0) index = totalSlides - 1;
  currentIndex = index;

  // ✅ แก้ให้เลื่อนทีละ 100% ของ viewport (เต็มหน้าจอ)
  slides.style.transform = `translateX(-${index * 100}%)`;

  dots.forEach(dot => dot.classList.remove("active"));
  dots[index].classList.add("active");
}

function moveSlide(step) { showSlide(currentIndex + step); }
function currentSlide(index) { showSlide(index); }
setInterval(() => { moveSlide(1); }, 5000);

// Script for Active Nav Link on Scroll
const navLinks = document.querySelectorAll(".nav-links a");
window.addEventListener("scroll", () => {
  let fromTop = window.scrollY + 80; // Adjusted offset
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
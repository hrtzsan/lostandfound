<?php 
include 'config.php'; 

// ดึงข้อมูลของหาย เอามาจากตารางฐานข้อมูล อย่าลืมเปลี่ยน
$sql = "SELECT * FROM found_items ORDER BY created_at DESC"; 
$result = $conn->query($sql); 
?> 

<!DOCTYPE html> 
<html lang="th"> 
<head> 
 <meta charset="UTF-8"> 
 <meta name="viewport" content="width=device-width, initial-scale=1.0">  
 <title>รายชื่อของที่พบเจอ</title> 
 <style> 
 body { 
     font-family: Arial, sans-serif; 
     margin: 20px; 
     background-color: #f5f5f5; 
 } 
 .container { 
     background: white; 
     padding: 20px; 
     border-radius: 10px; 
     box-shadow: 0 0 10px rgba(0,0,0,0.1); 
 } 
 table { 
     width: 100%; 
     border-collapse: collapse; 
     margin-top: 20px;
 } 
 th, td { 
     padding: 12px; 
     text-align: left; 
     border-bottom: 1px solid #ddd; 
 } 
 th { 
     background-color: #f2f2f2; 
     font-weight: bold; 
 } 
 tr:hover { 
     background-color: #f5f5f5; 
 } 
 .item-image { 
     width: 50px; 
     height: 50px; 
     border-radius: 4px; 
     object-fit: cover; 
 } 
 .no-image { 
     width: 50px; 
     height: 50px; 
     background-color: #ddd; 
     border-radius: 4px; 
     display: flex; 
     align-items: center; 
     justify-content: center; 
     font-size: 12px; 
     color: #666; 
 } 
 a.add-btn {
     display:inline-block;
     margin-bottom:10px;
     padding:6px 12px;
     background:green;
     color:#fff;
     border-radius:4px;
     text-decoration:none;
 }
 </style> 
</head> 
<body> 
 <div class="container"> 
 <h2>รายชื่อของที่พบเจอ</h2> 
 <p><a href="post_lost.php" class="add-btn">โพสต์ของที่พบเจอใหม่</a></p>  

 <?php if ($result->num_rows > 0): ?> 
 <table> 
 <tr> 
     <th>รูปภาพ</th> 
     <th>ชื่อสิ่งของ</th> 
     <th>รายละเอียด</th>
     <th>สถานที่</th>
     <th>วันที่พบ</th>
     <th>สถานะ</th>
     <th>ติดต่อ</th>
     <th>วันที่โพสต์</th>
 </tr> 
 <?php while($row = $result->fetch_assoc()): ?> 
 <tr> 
     <td> 
     <?php if ($row['image'] && file_exists($row['image'])): ?>  
         <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Item" class="item-image"> 
     <?php else: ?> 
         <div class="no-image">ไม่มีรูป</div> 
     <?php endif; ?> 
     </td> 
     <td><?php echo htmlspecialchars($row['title']); ?></td>
     <td><?php echo htmlspecialchars($row['description']); ?></td>
     <td><?php echo htmlspecialchars($row['location'] ?? '-'); ?></td>
     <td><?php echo htmlspecialchars($row['lost_date'] ?? '-'); ?></td>
     <td><?php echo htmlspecialchars($row['status']); ?></td>
     <td><?php echo htmlspecialchars($row['contact']); ?></td>
     <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
 </tr> 
 <?php endwhile; ?> 
 </table> 
 <?php else: ?> 
 <p>ยังไม่มีโพสต์ในระบบ</p> 
 <?php endif; ?> 
 </div> 
</body> 
</html> 

<?php 
$conn->close();
?>

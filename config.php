<?php 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "member_system"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { 
 die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error); 
} 
$conn->set_charset("utf8"); 
?>
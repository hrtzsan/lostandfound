<?php
include 'db.php'; // นี่จะมีตัวแปร $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['found_title'];
    $description = $_POST['found_description'];
    $found_date = $_POST['foundDate'];
    $found_place = $_POST['found_location'];
    $contact_info = $_POST['found_contact'];
    $status = $_POST['found_status'];

    // อัพโหลดรูปภาพ
    $image = '';
    if (isset($_FILES['found_item_image']) && $_FILES['found_item_image']['error'] === 0) {
        $image = basename($_FILES['found_item_image']['name']);
        $target_dir = "uploads/";
        move_uploaded_file($_FILES['found_item_image']['tmp_name'], $target_dir . $image);
    }

    // ใช้ $pdo แทน $conn
    $stmt = $pdo->prepare("INSERT INTO found_items (item_name, description, found_date, found_place, contact_info, status, image) 
                           VALUES (:item_name, :description, :found_date, :found_place, :contact_info, :status, :image)");

    $stmt->execute([
        ':item_name' => $item_name,
        ':description' => $description,
        ':found_date' => $found_date,
        ':found_place' => $found_place,
        ':contact_info' => $contact_info,
        ':status' => $status,
        ':image' => $image
    ]);

        // แจ้งเตือนและกลับไปหน้า lost_list.php
        echo "<script>alert('โพสต์แจ้งของหายสำเร็จ!'); window.location.href='index.php';</script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }
}
?>

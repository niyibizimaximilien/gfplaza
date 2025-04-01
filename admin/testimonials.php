<?php
require_once __DIR__ . '/../includes/auth.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_testimonial'])) {
    $name = trim($_POST['name']);
    $position = trim($_POST['position']);
    $content = trim($_POST['content']);
    $rating = (int)$_POST['rating'];
    $imagePath = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['image']['type'];
        
        if (in_array($fileType, $allowedTypes)) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = 'testimonial_' . time() . '.' . $ext;
            $uploadPath = '../uploads/testimonials/' . $imageName;
            
            if (!file_exists('../uploads/testimonials')) {
                mkdir('../uploads/testimonials', 0755, true);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $imagePath = 'testimonials/' . $imageName;
            }
        }
    }

    if (!empty($name) && !empty($content)) {
        $stmt = $db->prepare("INSERT INTO testimonials (name, position, content, rating, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $position, $content, $rating, $imagePath]);
        $_SESSION['success'] = "Testimonial added successfully!";
    } else {
        $_SESSION['error'] = "Name and content are required";
    }
    header("Location: testimonials.php");
    exit();
}

// ... rest of your admin testimonials.php code ...
<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $db->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$id]);
    $_SESSION['success'] = "Testimonial deleted successfully!";
}

header("Location: testimonials.php");
exit();
<?php
require_once '../includes/config.php';
require_once 'includes/auth.php';

// Get counts for dashboard
$rooms_count = $db->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
$bookings_count = $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$active_jobs_count = $db->query("SELECT COUNT(*) FROM jobs WHERE is_active = 1")->fetchColumn();
$applicants_count = $db->query("SELECT COUNT(*) FROM job_applications")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GF Plaza - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <h1>Admin Dashboard</h1>
        
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Rooms</h3>
                <p><?php echo $rooms_count; ?></p>
                <a href="rooms.php">Manage Rooms</a>
            </div>
            
            <div class="card">
                <h3>Total Bookings</h3>
                <p><?php echo $bookings_count; ?></p>
                <a href="bookings.php">View Bookings</a>
            </div>
            
            <div class="card">
                <h3>Active Jobs</h3>
                <p><?php echo $active_jobs_count; ?></p>
                <a href="jobs.php">Manage Jobs</a>
            </div>
            
            <div class="card">
                <h3>Job Applicants</h3>
                <p><?php echo $applicants_count; ?></p>
                <a href="applicants.php">View Applicants</a>
            </div>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
<?php
// Correct path to config.php (assuming it's in project root)
require_once __DIR__ . '/../config.php';

// Verify config loaded
if (!isset($db)) {
    die("Configuration error: Database connection not established");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $jobId = (int)$_POST['job_id'];
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $nationalId = trim($_POST['national_id']);
    $passportNumber = trim($_POST['passport_number']);
    $gender = trim($_POST['gender']);
    
    // File upload validation
    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        die("CV upload is required");
    }
    
    $fileType = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
    if ($fileType !== 'pdf') {
        die("Only PDF files are allowed for CV");
    }
    
    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($phone) || !preg_match('/^[0-9]{10,15}$/', $phone)) {
        die("Invalid input data");
    }
    
    // Generate CV filename
    $cvFilename = preg_replace('/[^A-Za-z0-9]/', '', $firstName . $lastName) . 'CV.' . $fileType;
    $uploadPath = UPLOAD_DIR . '/' . $cvFilename;
    
    // Move uploaded file
    if (!move_uploaded_file($_FILES['cv']['tmp_name'], $uploadPath)) {
        die("Failed to upload CV");
    }
    
    try {
        // Create application
        $stmt = $db->prepare("INSERT INTO job_applications 
                            (job_id, first_name, last_name, phone, email, national_id, passport_number, cv_path, gender) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $jobId, $firstName, $lastName, $phone, $email, 
            $nationalId, $passportNumber, $cvFilename, $gender
        ]);
        
        // Redirect with success
        header("Location: ../index.php?application_success=1");
        exit();
    } catch (PDOException $e) {
        if (file_exists($uploadPath)) unlink($uploadPath);
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
    exit();
}
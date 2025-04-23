<?php
require_once '../includes/config.php';
require_once 'includes/auth.php';

// Check if filtering by job
$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

// Get applicants
$query = "SELECT ja.*, j.title as job_title 
          FROM job_applications ja
          LEFT JOIN jobs j ON ja.job_id = j.id";
          
if ($job_id > 0) {
    $query .= " WHERE ja.job_id = ?";
    $applicants = $db->prepare($query);
    $applicants->execute([$job_id]);
} else {
    $applicants = $db->query($query);
}

$applicants = $applicants->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GF Plaza - Job Applicants</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <h1>Job Applicants</h1>
        
        <?php if ($job_id > 0): ?>
            <a href="jobs.php" class="btn btn-back">Back to Jobs</a>
        <?php endif; ?>
        
        <div class="table-section">
            <div class="search-box">
                <input type="text" id="applicantSearch" placeholder="Search applicants...">
                <button class="btn">Search</button>
            </div>
            
            <div class="table-responsive">
                <table id="applicantsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Job Title</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applicants as $applicant): ?>
                        <tr>
                            <td><?php echo $applicant['id']; ?></td>
                            <td><?php echo htmlspecialchars($applicant['first_name'] . ' ' . htmlspecialchars($applicant['last_name'])) ?></td>
                            <td><?php echo htmlspecialchars($applicant['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['phone']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                            <td><?php echo ucfirst($applicant['gender']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($applicant['application_date'])); ?></td>
                            <td>
                                <button class="btn btn-sm view-applicant" data-id="<?php echo $applicant['id']; ?>">View</button>
                                <a href="../uploads/<?php echo htmlspecialchars($applicant['cv_path']); ?>" 
                                   class="btn btn-sm" download>Download CV</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Applicant Details Modal -->
    <div id="applicantModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="applicantModalContent"></div>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
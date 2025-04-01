<?php
require_once '../../includes/config.php';

if (!isset($_GET['id'])) {
    die("Applicant ID not provided");
}

$applicantId = (int)$_GET['id'];
$applicant = $db->prepare("
    SELECT ja.*, j.title as job_title 
    FROM job_applications ja
    LEFT JOIN jobs j ON ja.job_id = j.id
    WHERE ja.id = ?
");
$applicant->execute([$applicantId]);
$applicant = $applicant->fetch(PDO::FETCH_ASSOC);

if (!$applicant) {
    die("Applicant not found");
}
?>
<div class="applicant-details">
    <h2>Applicant Details</h2>
    
    <div class="details-grid">
        <div class="detail">
            <strong>Job Title:</strong>
            <span><?php echo htmlspecialchars($applicant['job_title']); ?></span>
        </div>
        <div class="detail">
            <strong>Name:</strong>
            <span><?php echo htmlspecialchars($applicant['first_name'] . ' ' . htmlspecialchars($applicant['last_name']); ?></span>
        </div>
        <div class="detail">
            <strong>Phone:</strong>
            <span><?php echo htmlspecialchars($applicant['phone']); ?></span>
        </div>
        <div class="detail">
            <strong>Email:</strong>
            <span><?php echo htmlspecialchars($applicant['email'] ?: 'N/A'); ?></span>
        </div>
        <div class="detail">
            <strong>Gender:</strong>
            <span><?php echo ucfirst($applicant['gender']); ?></span>
        </div>
        <div class="detail">
            <strong>National ID:</strong>
            <span><?php echo htmlspecialchars($applicant['national_id'] ?: 'N/A'); ?></span>
        </div>
        <div class="detail">
            <strong>Passport Number:</strong>
            <span><?php echo htmlspecialchars($applicant['passport_number'] ?: 'N/A'); ?></span>
        </div>
        <div class="detail">
            <strong>Application Date:</strong>
            <span><?php echo date('M j, Y', strtotime($applicant['application_date'])); ?></span>
        </div>
    </div>
    
    <div class="cv-download">
        <h3>Curriculum Vitae</h3>
        <a href="../uploads/<?php echo htmlspecialchars($applicant['cv_path']); ?>" class="btn" download>
            Download CV
        </a>
    </div>
</div>
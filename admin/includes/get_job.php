<?php
require_once '../../includes/config.php';

if (!isset($_GET['id'])) {
    die("Job ID not provided");
}

$jobId = (int)$_GET['id'];
$job = $db->prepare("SELECT * FROM jobs WHERE id = ?");
$job->execute([$jobId]);
$job = $job->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die("Job not found");
}

if (isset($_GET['edit'])) {
    // Return JSON for edit form
    header('Content-Type: application/json');
    echo json_encode($job);
    exit();
}

// HTML for view modal
?>
<div class="job-details">
    <h2><?php echo htmlspecialchars($job['title']); ?></h2>
    
    <div class="details-grid">
        <div class="detail">
            <strong>Posted:</strong>
            <span><?php echo date('M j, Y', strtotime($job['posted_at'])); ?></span>
        </div>
        <div class="detail">
            <strong>Deadline:</strong>
            <span><?php echo date('M j, Y', strtotime($job['deadline'])); ?></span>
        </div>
        <div class="detail">
            <strong>Gender Requirement:</strong>
            <span><?php echo ucfirst($job['gender_requirement']); ?></span>
        </div>
        <div class="detail">
            <strong>Status:</strong>
            <span class="status-badge <?php echo $job['is_active'] ? 'active' : 'inactive'; ?>">
                <?php echo $job['is_active'] ? 'Active' : 'Inactive'; ?>
            </span>
        </div>
    </div>
    
    <div class="description">
        <h3>Job Description</h3>
        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
    </div>
    
    <div class="applicants-count">
        <?php
        $count = $db->prepare("SELECT COUNT(*) FROM job_applications WHERE job_id = ?");
        $count->execute([$jobId]);
        $count = $count->fetchColumn();
        ?>
        <strong>Applicants:</strong> <?php echo $count; ?>
    </div>
</div>
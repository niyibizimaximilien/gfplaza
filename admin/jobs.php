<?php
require_once '../includes/config.php';
require_once 'includes/auth.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_job'])) {
        // Add new job
        $stmt = $db->prepare("INSERT INTO jobs (title, description, deadline, gender_requirement) 
                             VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['title'],
            $_POST['description'],
            $_POST['deadline'],
            $_POST['gender_requirement']
        ]);
        
        $_SESSION['success'] = "Job posted successfully!";
    } elseif (isset($_POST['update_job'])) {
        // Update job
        $stmt = $db->prepare("UPDATE jobs SET 
                            title = ?, description = ?, deadline = ?, 
                            gender_requirement = ?, is_active = ?
                            WHERE id = ?");
        $stmt->execute([
            $_POST['title'],
            $_POST['description'],
            $_POST['deadline'],
            $_POST['gender_requirement'],
            isset($_POST['is_active']) ? 1 : 0,
            $_POST['job_id']
        ]);
        
        $_SESSION['success'] = "Job updated successfully!";
    } elseif (isset($_POST['delete_job'])) {
        // Delete job
        $db->prepare("DELETE FROM jobs WHERE id = ?")->execute([$_POST['job_id']]);
        $_SESSION['success'] = "Job deleted successfully!";
    }
    
    header("Location: jobs.php");
    exit();
}

// Get all jobs
$jobs = $db->query("SELECT * FROM jobs ORDER BY posted_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GF Plaza - Manage Jobs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <h1>Manage Jobs</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="job-management">
            <!-- Add Job Form -->
            <div class="form-section">
                <h2>Post New Job</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Job Title</label>
                        <input type="text" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Deadline</label>
                            <input type="date" name="deadline" required>
                        </div>
                        <div class="form-group">
                            <label>Gender Requirement</label>
                            <select name="gender_requirement">
                                <option value="both">Both</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" name="add_job" class="btn">Post Job</button>
                </form>
            </div>
            
            <!-- Jobs Table -->
            <div class="table-section">
                <h2>Current Jobs</h2>
                <div class="search-box">
                    <input type="text" id="jobSearch" placeholder="Search jobs...">
                    <button class="btn">Search</button>
                </div>
                
                <div class="table-responsive">
                    <table id="jobsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Posted</th>
                                <th>Deadline</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?php echo $job['id']; ?></td>
                                <td><?php echo htmlspecialchars($job['title']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($job['posted_at'])); ?></td>
                                <td><?php echo date('M j, Y', strtotime($job['deadline'])); ?></td>
                                <td><?php echo ucfirst($job['gender_requirement']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $job['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $job['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm view-job" data-id="<?php echo $job['id']; ?>">View</button>
                                    <button class="btn btn-sm edit-job" data-id="<?php echo $job['id']; ?>">Edit</button>
                                    <a href="applicants.php?job_id=<?php echo $job['id']; ?>" class="btn btn-sm">Applicants</a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                        <button type="submit" name="delete_job" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Details Modal -->
    <div id="jobModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="jobModalContent"></div>
        </div>
    </div>

    <!-- Edit Job Modal -->
    <div id="editJobModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Edit Job</h2>
            <form id="editJobForm" method="POST">
                <input type="hidden" name="job_id" id="editJobId">
                
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="title" id="editJobTitle" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="editJobDescription" rows="5" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Deadline</label>
                        <input type="date" name="deadline" id="editJobDeadline" required>
                    </div>
                    <div class="form-group">
                        <label>Gender Requirement</label>
                        <select name="gender_requirement" id="editJobGender">
                            <option value="both">Both</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" id="editJobActive" value="1"> Active
                    </label>
                </div>
                
                <button type="submit" name="update_job" class="btn">Update Job</button>
            </form>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
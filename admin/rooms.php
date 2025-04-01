<?php
require_once '../includes/config.php';
require_once 'includes/auth.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_room'])) {
        // Add new room logic
        $stmt = $db->prepare("INSERT INTO rooms (name, size, width, height, floor, description, price, discount_price, features, status) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['size'],
            $_POST['width'],
            $_POST['height'],
            $_POST['floor'],
            $_POST['description'],
            $_POST['price'],
            $_POST['discount_price'],
            $_POST['features'],
            $_POST['status']
        ]);
        
        // Handle image upload
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $room_id = $db->lastInsertId();
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = "room_{$room_id}.{$ext}";
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/{$filename}");
            
            $db->prepare("UPDATE rooms SET image = ? WHERE id = ?")->execute([$filename, $room_id]);
        }
        
        $_SESSION['success'] = "Room added successfully!";
    } elseif (isset($_POST['update_room'])) {
        // Update room logic
        $stmt = $db->prepare("UPDATE rooms SET 
                            name = ?, size = ?, width = ?, height = ?, floor = ?, 
                            description = ?, price = ?, discount_price = ?, 
                            features = ?, status = ? 
                            WHERE id = ?");
        $stmt->execute([
            $_POST['name'],
            $_POST['size'],
            $_POST['width'],
            $_POST['height'],
            $_POST['floor'],
            $_POST['description'],
            $_POST['price'],
            $_POST['discount_price'],
            $_POST['features'],
            $_POST['status'],
            $_POST['room_id']
        ]);
        
        $_SESSION['success'] = "Room updated successfully!";
    } elseif (isset($_POST['delete_room'])) {
        // Delete room logic
        $db->prepare("DELETE FROM rooms WHERE id = ?")->execute([$_POST['room_id']]);
        $_SESSION['success'] = "Room deleted successfully!";
    }
    
    header("Location: rooms.php");
    exit();
}

// Get all rooms
$rooms = $db->query("SELECT * FROM rooms ORDER BY floor, name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GF Plaza - Manage Rooms</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <h1>Manage Rooms</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="room-management">
            <!-- Add Room Form -->
            <div class="form-section">
                <h2>Add New Room</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Room Name</label>
                        <input type="text" name="name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Size</label>
                            <input type="text" name="size">
                        </div>
                        <div class="form-group">
                            <label>Width (m)</label>
                            <input type="number" step="0.01" name="width">
                        </div>
                        <div class="form-group">
                            <label>Height (m)</label>
                            <input type="number" step="0.01" name="height">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Floor</label>
                            <input type="number" name="floor">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="available">Available</option>
                                <option value="booked">Booked</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Price (Rwf)</label>
                            <input type="number" step="0.01" name="price" required>
                        </div>
                        <div class="form-group">
                            <label>Discount Price (Rwf)</label>
                            <input type="number" step="0.01" name="discount_price">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Features (comma separated)</label>
                        <input type="text" name="features" placeholder="e.g., WiFi, AC, Parking">
                    </div>
                    
                    <div class="form-group">
                        <label>Room Image</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    
                    <button type="submit" name="add_room" class="btn">Add Room</button>
                </form>
            </div>
            
            <!-- Rooms Table -->
            <div class="table-section">
                <h2>All Rooms</h2>
                <div class="search-box">
                    <input type="text" id="roomSearch" placeholder="Search rooms...">
                    <button class="btn">Search</button>
                </div>
                
                <div class="table-responsive">
                    <table id="roomsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Floor</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                            <tr>
                                <td><?php echo $room['id']; ?></td>
                                <td><?php echo htmlspecialchars($room['name']); ?></td>
                                <td><?php echo $room['floor']; ?></td>
                                <td>KSh <?php echo number_format($room['price'], 2); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $room['status']; ?>">
                                        <?php echo ucfirst($room['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm view-room" data-id="<?php echo $room['id']; ?>">View</button>
                                    <button class="btn btn-sm edit-room" data-id="<?php echo $room['id']; ?>">Edit</button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                        <button type="submit" name="delete_room" class="btn btn-sm btn-danger" 
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

    <!-- Room Details Modal -->
    <div id="roomModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="roomModalContent"></div>
        </div>
    </div>

    <!-- Edit Room Modal -->
    <div id="editRoomModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Edit Room</h2>
            <form id="editRoomForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="room_id" id="editRoomId">
                <!-- Form fields will be populated by JavaScript -->
                <div class="form-group">
                    <label>Room Name</label>
                    <input type="text" name="name" id="editName" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Size</label>
                        <input type="text" name="size" id="editSize">
                    </div>
                    <div class="form-group">
                        <label>Width (m)</label>
                        <input type="number" step="0.01" name="width" id="editWidth">
                    </div>
                    <div class="form-group">
                        <label>Height (m)</label>
                        <input type="number" step="0.01" name="height" id="editHeight">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Floor</label>
                        <input type="number" name="floor" id="editFloor">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="editStatus">
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price (Rwf)</label>
                        <input type="number" step="0.01" name="price" id="editPrice" required>
                    </div>
                    <div class="form-group">
                        <label>Discount Price (Rwf)</label>
                        <input type="number" step="0.01" name="discount_price" id="editDiscountPrice">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="editDescription" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Features (comma separated)</label>
                    <input type="text" name="features" id="editFeatures" placeholder="e.g., WiFi, AC, Parking">
                </div>
                
                <div class="form-group">
                    <label>Current Image</label>
                    <div id="currentImageContainer"></div>
                    <label>Change Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                
                <button type="submit" name="update_room" class="btn">Update Room</button>
            </form>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
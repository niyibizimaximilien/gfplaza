<?php
require_once '../../includes/config.php';

if (!isset($_GET['id'])) {
    die("Room ID not provided");
}

$roomId = (int)$_GET['id'];
$room = $db->prepare("SELECT * FROM rooms WHERE id = ?");
$room->execute([$roomId]);
$room = $room->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found");
}

if (isset($_GET['edit'])) {
    // Return JSON for edit form
    header('Content-Type: application/json');
    echo json_encode($room);
    exit();
}

// HTML for view modal
?>
<div class="room-details">
    <h2><?php echo htmlspecialchars($room['name']); ?></h2>
    
    <?php if ($room['image']): ?>
    <div class="room-image">
        <img src="../uploads/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" style="max-width: 100%;">
    </div>
    <?php endif; ?>
    
    <div class="details-grid">
        <div class="detail">
            <strong>Size:</strong>
            <span><?php echo htmlspecialchars($room['size'] ?: 'N/A'); ?></span>
        </div>
        <div class="detail">
            <strong>Dimensions:</strong>
            <span><?php echo htmlspecialchars(($room['width'] && $room['height']) ? "{$room['width']}m × {$room['height']}m" : 'N/A'); ?></span>
        </div>
        <div class="detail">
            <strong>Floor:</strong>
            <span><?php echo htmlspecialchars($room['floor'] ?: 'N/A'); ?></span>
        </div>
        <div class="detail">
            <strong>Status:</strong>
            <span class="status-badge <?php echo $room['status']; ?>"><?php echo ucfirst($room['status']); ?></span>
        </div>
        <div class="detail">
            <strong>Price:</strong>
            <span>Rwf <?php echo number_format($room['price'], 2); ?></span>
        </div>
        <?php if ($room['discount_price']): ?>
        <div class="detail">
            <strong>Discount Price:</strong>
            <span>Rwf <?php echo number_format($room['discount_price'], 2); ?></span>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if ($room['description']): ?>
    <div class="description">
        <h3>Description</h3>
        <p><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if ($room['features']): ?>
    <div class="features">
        <h3>Features</h3>
        <ul>
            <?php 
            $features = explode(',', $room['features']);
            foreach ($features as $feature): 
                $feature = trim($feature);
                if ($feature):
            ?>
            <li><?php echo htmlspecialchars($feature); ?></li>
            <?php 
                endif;
            endforeach; 
            ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
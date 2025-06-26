<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$rooms = getAvailableRooms();

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_room'])) {
    $roomId = $_POST['room_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $message = $_POST['message'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (room_id, customer_name, customer_email, customer_phone, move_in_date, requirements) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$roomId, $name, $email, $phone, $date, $message]);
        
        // Update room status to booked
        $stmt = $pdo->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?");
        $stmt->execute([$roomId]);
        
        header('Location: index.php?booking=success');
        exit;
    } catch (PDOException $e) {
        header('Location: index.php?booking=error&message=' . urlencode($e->getMessage()));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Premium Business Tower</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <a href="#" class="logo">
            <div class="logo-icon">GF</div>
            <div class="logo-text"><?php echo SITE_NAME; ?></div>
        </a>
        
        <nav class="desktop-nav">
            <a href="#home">Home</a>
            <a href="#spaces">Spaces</a>
            <a href="#features">Features</a>
            <a href="#contact">Contact</a>
        </nav>
        
        <div class="menu-toggle" id="menuToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="close-menu" id="closeMenu">✕</div>
        <a href="#home">Home</a>
        <a href="#spaces">Spaces</a>
        <a href="#features">Features</a>
        <a href="#contact">Contact</a>
    </div>
    
    <div class="overlay" id="overlay"></div>
    
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-image">
            <img src="assets/images/tower-hero.jpg" alt="<?php echo SITE_NAME; ?> Tower">
        </div>
        <div class="hero-content">
            <h1>Premium Business Spaces at <?php echo SITE_NAME; ?></h1>
            <p>Discover the perfect office space designed for productivity and success in the heart of the city.</p>
            <a href="#spaces" class="hero-btn">View Available Spaces</a>
        </div>
    </section>
    
    <!-- Main Content -->
    <div class="container">
        <!-- Features Section -->
        <section id="features">
            <div class="section-title">
                <h2>Why Choose <?php echo SITE_NAME; ?></h2>
                <p>Our business tower offers unparalleled amenities and services designed to help your business thrive</p>
            </div>
            
            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h3>High-Speed Internet</h3>
                    <p>Fiber-optic connectivity throughout the building with dedicated bandwidth options</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>24/7 Security</h3>
                    <p>State-of-the-art security systems with CCTV monitoring and professional security personnel</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3>Premium Amenities</h3>
                    <p>Conference rooms, business lounge, cafeteria, and fitness center available to all tenants</p>
                </div>
            </div>
        </section>
        
        <!-- Spaces Section -->
        <section id="spaces">
            <div class="section-title">
                <h2>Available Spaces</h2>
                <p>Find the perfect office space tailored to your business needs and budget</p>
            </div>
            
            <div class="rooms-grid">
                <?php if (empty($rooms)): ?>
                    <div class="no-rooms">
                        <p>Currently all spaces are occupied. Please check back later or contact us for upcoming availabilities.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($rooms as $room): ?>
                        <div class="room-card">
                            <div class="room-image">
                                <?php if (!empty($room['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($room['image_path']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
                                <?php else: ?>
                                    <div class="image-placeholder">
                                        <i class="fas fa-building"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="room-status">Available</span>
                            </div>
                            <div class="room-details">
                                <h3 class="room-title"><?php echo htmlspecialchars($room['name']); ?></h3>
                                
                                <div class="room-specs">
                                    <div class="room-spec">
                                        <i class="fas fa-ruler-combined"></i>
                                        <?php echo htmlspecialchars($room['size']); ?>
                                    </div>
                                    <div class="room-spec">
                                        <i class="fas fa-layer-group"></i>
                                        <?php echo htmlspecialchars($room['floor']); ?>
                                    </div>
                                    <?php if (!empty($room['height']) && !empty($room['width'])): ?>
                                    <div class="room-spec">
                                        <i class="fas fa-expand"></i>
                                        <?php echo htmlspecialchars($room['height']); ?> x <?php echo htmlspecialchars($room['width']); ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($room['view'])): ?>
                                    <div class="room-spec">
                                        <i class="fas fa-binoculars"></i>
                                        <?php echo htmlspecialchars($room['view']); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($room['description'])): ?>
                                <p class="room-description"><?php echo htmlspecialchars($room['description']); ?></p>
                                <?php endif; ?>
                                
                                <p class="room-price">$<?php echo number_format($room['price'], 2); ?>/month</p>
                                
                                <button class="book-btn" 
                                        data-room-id="<?php echo $room['id']; ?>"
                                        data-room-name="<?php echo htmlspecialchars($room['name']); ?>">
                                    Book Now
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Contact Section -->
        <section id="contact">
            <div class="section-title">
                <h2>Contact Us</h2>
                <p>Get in touch to schedule a tour or ask about our available spaces</p>
            </div>
            
            <div class="contact-container">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Location</h3>
                            <p><?php echo SITE_ADDRESS; ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Phone</h3>
                            <p><?php echo SITE_PHONE; ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Email</h3>
                            <p><?php echo SITE_EMAIL; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <form action="process-contact.php" method="POST">
                        <div class="form-group">
                            <label for="contact-name">Your Name</label>
                            <input type="text" id="contact-name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-email">Email Address</label>
                            <input type="email" id="contact-email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-message">Message</label>
                            <textarea id="contact-message" name="message" required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    
    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Book <span id="modalRoomName"></span></h3>
            <form id="bookingForm" method="POST" action="process-booking.php">
                <input type="hidden" name="room_id" id="modalRoomId" value="">
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label for="date">Move-in Date</label>
                    <input type="date" id="date" name="date" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Additional Requirements</label>
                    <textarea id="message" name="message" rows="4"></textarea>
                </div>
                
                <button type="submit" class="submit-btn" name="book_room">Submit Booking Request</button>
            </form>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h3>About <?php echo SITE_NAME; ?></h3>
                <p><?php echo SITE_NAME; ?> is a premium business tower offering state-of-the-art office spaces.</p>
            </div>
            
            <div class="footer-col">
                <h3>Quick Links</h3>
                <a href="#home">Home</a>
                <a href="#spaces">Spaces</a>
                <a href="#contact">Contact</a>
            </div>
            
            <div class="footer-col">
                <h3>Contact Info</h3>
                <p><i class="fas fa-map-marker-alt"></i> <?php echo SITE_ADDRESS; ?></p>
                <p><i class="fas fa-phone-alt"></i> <?php echo SITE_PHONE; ?></p>
                <p><i class="fas fa-envelope"></i> <?php echo SITE_EMAIL; ?></p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMenu = document.getElementById('closeMenu');
        const overlay = document.getElementById('overlay');

        function openMobileMenu() {
            mobileMenu.classList.add('active');
            overlay.classList.add('active');
            menuToggle.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('active');
            overlay.classList.remove('active');
            menuToggle.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        menuToggle.addEventListener('click', openMobileMenu);
        closeMenu.addEventListener('click', closeMobileMenu);
        overlay.addEventListener('click', closeMobileMenu);

        // Booking Modal
        const bookButtons = document.querySelectorAll('.book-btn');
        const bookingModal = document.getElementById('bookingModal');
        const modalRoomName = document.getElementById('modalRoomName');
        const modalRoomId = document.getElementById('modalRoomId');
        const closeModal = document.querySelector('.close-modal');

        bookButtons.forEach(button => {
            button.addEventListener('click', () => {
                const roomId = button.getAttribute('data-room-id');
                const roomName = button.getAttribute('data-room-name');
                
                modalRoomName.textContent = roomName;
                modalRoomId.value = roomId;
                bookingModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });

        closeModal.addEventListener('click', () => {
            bookingModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        window.addEventListener('click', (e) => {
            if (e.target === bookingModal) {
                bookingModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Show success/error messages
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('booking')) {
                if (urlParams.get('booking') === 'success') {
                    alert('Booking submitted successfully! We will contact you shortly.');
                } else if (urlParams.get('booking') === 'error') {
                    alert('Error submitting booking: ' + decodeURIComponent(urlParams.get('message')));
                }
            }
        });
    </script>
</body>
</html>

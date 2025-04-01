<?php
require_once 'includes/config.php';

// Get available rooms
$rooms = $db->query("SELECT * FROM rooms WHERE status = 'available' ORDER BY floor, price")->fetchAll(PDO::FETCH_ASSOC);

// Get active jobs
$jobs = $db->query("SELECT * FROM jobs WHERE is_active = 1 AND deadline >= CURDATE() ORDER BY posted_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Get testimonials
$testimonials = $db->query("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GF Plaza - Premium Office Spaces</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" id="theme-style" href="assets/css/light.css">
</head>
<body>
    <!-- Header/Navigation -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <span style="color: #e74c3c;">GF</span> Plaza
                </a>
            </div>
            
            <nav class="nav">
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#spaces">Available Spaces</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                    <li><a href="#jobs">Job Opportunities</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
                
                <div class="theme-switcher">
                    <button id="theme-toggle">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </nav>
            
            <button class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <button class="close-menu">&times;</button>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#spaces">Available Spaces</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
            <li><a href="#jobs">Job Opportunities</a></li>
            <li><a href="#contact">Contact Us</a></li>
        </ul>
        
        <div class="mobile-theme">
            <button id="mobile-theme-toggle">
                <i class="fas fa-moon"></i> Toggle Theme
            </button>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h1 style="color:#f9f9f9;">Premium Office Spaces at GF Plaza</h1>
                <p>Experience comfort, security, and high-speed connectivity in our state-of-the-art office spaces</p>
                <a href="#spaces" class="btn">View Available Spaces</a>
            </div>
        </div>
    </section>

    <!-- Why Choose GF Plaza -->
    <section class="features">
        <div class="container">
            <h2>Why Choose GF Plaza</h2>
            <div class="features-grid">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h3>High-Speed Internet</h3>
                    <p>Fiber-optic connectivity with 99.9% uptime guarantee for uninterrupted productivity</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>24/7 Security</h3>
                    <p>Round-the-clock surveillance and professional security personnel</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3>Premium Amenities</h3>
                    <p>Meeting rooms, lounge areas, and concierge services available</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-parking"></i>
                    </div>
                    <h3>Ample Parking</h3>
                    <p>Secure underground parking with EV charging stations</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Available Spaces -->
    <section class="spaces" id="spaces">
        <div class="container">
            <h2>Available Spaces</h2>
            
            <?php if (empty($rooms)): ?>
                <div class="no-spaces">
                    <p>Currently all spaces are occupied. Please check back later.</p>
                </div>
            <?php else: ?>
                <div class="spaces-grid">
                    <?php foreach ($rooms as $room): ?>
                        <div class="space-card">
                            <?php if ($room['image']): ?>
                                <div class="space-image">
                                    <img src="uploads/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="space-details">
                                <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                                <p class="floor">Floor <?php echo $room['floor']; ?></p>
                                
                                <div class="space-price">
                                    <?php if ($room['discount_price']): ?>
                                        <span class="original-price">Rwf <?php echo number_format($room['price'], 2); ?></span>
                                        <span class="discount-price">Rwf <?php echo number_format($room['discount_price'], 2); ?></span>
                                    <?php else: ?>
                                        <span class="current-price">Rwf <?php echo number_format($room['price'], 2); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="space-status available">
                                    <i class="fas fa-check-circle"></i> Available
                                </div>
                                
                                <button class="btn btn-book" data-room-id="<?php echo $room['id']; ?>">Book Now</button>
                                
                                <!-- Booking Form (Hidden by default) -->
                                <div class="booking-form" id="booking-form-<?php echo $room['id']; ?>">
                                    <h4>Book This Space</h4>
                                    <form class="booking-form-inner" method="POST" action="includes/book_room.php">
                                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                        
                                        <div class="form-group">
                                            <label>Full Name *</label>
                                            <input type="text" name="customer_name" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Phone Number *</label>
                                            <input type="tel" name="phone" required>
                                        </div>
                                        
                                        <button type="submit" class="btn">Submit Booking</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    

    <!-- Testimonials
    <section class="testimonials" id="testimonials">
        <div class="container">
            <h2>What Our Clients Say</h2>
            
            <div class="testimonials-grid">
                <?php if (empty($testimonials)): ?>
                    <p>No testimonials available yet.</p>
                <?php else: ?>
                    <?php foreach ($testimonials as $testimonial): ?>
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p>"<?php echo htmlspecialchars($testimonial['content']); ?>"</p>
                            </div>
                            <div class="testimonial-author">
                                <h4><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                <?php if ($testimonial['role']): ?>
                                    <p><?php echo htmlspecialchars($testimonial['role']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section> -->

    <!-- Testimonials Section -->
<section class="testimonials" style="padding: 60px 0; background: #f9f9f9;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 40px;">Client Testimonials</h2>
        
        <?php
        try {
            $testimonials = $db->query("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 3");
            
            if ($testimonials->rowCount() > 0): ?>
                <div class="testimonial-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                    <?php foreach ($testimonials as $testimonial): ?>
                        <div class="testimonial-card" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <div class="rating" style="color: #FFD700; font-size: 20px; margin-bottom: 10px;">
                                <?= str_repeat('★', $testimonial['rating']) . str_repeat('☆', 5 - $testimonial['rating']) ?>
                            </div>
                            <p style="font-style: italic; margin-bottom: 20px;">"<?= htmlspecialchars($testimonial['content']) ?>"</p>
                            <div class="author" style="font-weight: bold;">
                                <?= htmlspecialchars($testimonial['name']) ?>
                                <?php if (!empty($testimonial['position'])): ?>
                                    <span style="display: block; font-weight: normal; font-size: 0.9em; color: #666;">
                                        <?= htmlspecialchars($testimonial['position']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center;">No testimonials available yet.</p>
            <?php endif;
        } catch (PDOException $e) {
            echo "<p style='text-align: center; color: red;'>Error loading testimonials. Please try again later.</p>";
        }
        ?>
    </div>
</section>

    <!-- Job Opportunities -->
    <section class="jobs" id="jobs">
        <div class="container">
            <h2>Job Opportunities</h2>
            
            <?php if (empty($jobs)): ?>
                <div class="no-jobs">
                    <p>There are currently no job openings. Please check back later.</p>
                </div>
            <?php else: ?>
                <div class="jobs-grid">
                    <?php foreach ($jobs as $job): ?>
                        <div class="job-card">
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p class="job-deadline">Apply by: <?php echo date('M j, Y', strtotime($job['deadline'])); ?></p>
                            
                            <div class="job-description">
                                <?php echo nl2br(htmlspecialchars(substr($job['description'], 0, 200))); ?>
                                <?php if (strlen($job['description']) > 200): ?>...<?php endif; ?>
                            </div>
                            
                            <div class="job-gender">
                                <strong>Gender:</strong> <?php echo ucfirst($job['gender_requirement']); ?>
                            </div>
                            
                            <button class="btn btn-apply" data-job-id="<?php echo $job['id']; ?>">Apply Now</button>
                            
                            <!-- Application Form (Hidden by default) -->
                            <div class="application-form" id="application-form-<?php echo $job['id']; ?>">
                                <h4>Apply for <?php echo htmlspecialchars($job['title']); ?></h4>
                                <form class="application-form-inner" method="POST" action="includes/apply_job.php" enctype="multipart/form-data">
                                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>First Name *</label>
                                            <input type="text" name="first_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name *</label>
                                            <input type="text" name="last_name" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Phone Number *</label>
                                        <input type="tel" name="phone" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email">
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>National ID (16 digits)</label>
                                            <input type="text" name="national_id" pattern="\d{16}" title="16 digit National ID">
                                        </div>
                                        <div class="form-group">
                                            <label>Passport Number</label>
                                            <input type="text" name="passport_number">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender">
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Upload CV (PDF only) *</label>
                                        <input type="file" name="cv" accept=".pdf" required>
                                        <small>File name should be YourNameCV.pdf (e.g., JohnDoeCV.pdf)</small>
                                    </div>
                                    
                                    <button type="submit" class="btn">Submit Application</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact Us -->
    <section class="contact" id="contact">
        <div class="container">
            <h2>Contact Us</h2>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Location</h3>
                            <p>123 Business Avenue, Nairobi, Kenya</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Phone</h3>
                            <p>+254 712 345 678</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <p>info@gfplaza.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Headquarters</h3>
                            <p>GF Plaza Tower, 10th Floor</p>
                        </div>
                    </div>
                    
                    <div class="contact-social">
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Send Us a Message</h3>
                    <form method="POST" action="includes/send_message.php">
                        <div class="form-group">
                            <label>Your Name</label>
                            <input type="text" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Your Email</label>
                            <input type="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <div class="footer-logo">
                        <a href="index.php">
                            <span style="color: #e74c3c;">GF</span> Plaza
                        </a>
                    </div>
                    <p>Premium office spaces designed for productivity, comfort, and success.</p>
                </div>
                
                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#spaces">Available Spaces</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li><a href="#jobs">Job Opportunities</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Business Avenue, Nairobi</p>
                    <p><i class="fas fa-phone-alt"></i> +254 712 345 678</p>
                    <p><i class="fas fa-envelope"></i> info@gfplaza.com</p>
                </div>
                
                <div class="footer-social">
                    <h3>Follow Us</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> GF Plaza. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Booking Success Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-body">
                <h3>Booking Successful!</h3>
                <p id="bookingMessage"></p>
                <div id="paymentInstructions"></div>
            </div>
        </div>
    </div>

    <!-- Application Success Modal -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-body">
                <h3>Application Submitted!</h3>
                <p>Thank you for applying. We'll review your application and get back to you soon.</p>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/theme.js"></script>
</body>
</html>

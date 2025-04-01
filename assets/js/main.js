document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeMenu = document.querySelector('.close-menu');
    
    hamburger.addEventListener('click', function() {
        mobileMenu.classList.add('active');
    });
    
    closeMenu.addEventListener('click', function() {
        mobileMenu.classList.remove('active');
    });
    
    // Close mobile menu when clicking on a link
    const mobileLinks = document.querySelectorAll('.mobile-menu a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
        });
    });
    
    // Booking Form Toggle
    const bookButtons = document.querySelectorAll('.btn-book');
    bookButtons.forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-room-id');
            const bookingForm = document.getElementById(`booking-form-${roomId}`);
            
            if (bookingForm.style.display === 'block') {
                bookingForm.style.display = 'none';
                this.textContent = 'Book Now';
            } else {
                bookingForm.style.display = 'block';
                this.textContent = 'Hide Form';
            }
        });
    });
    
    // Application Form Toggle
    const applyButtons = document.querySelectorAll('.btn-apply');
    applyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const jobId = this.getAttribute('data-job-id');
            const applicationForm = document.getElementById(`application-form-${jobId}`);
            
            if (applicationForm.style.display === 'block') {
                applicationForm.style.display = 'none';
                this.textContent = 'Apply Now';
            } else {
                applicationForm.style.display = 'block';
                this.textContent = 'Hide Form';
            }
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Show booking success modal if URL has booking_success parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('booking_success')) {
        const bookingModal = document.getElementById('bookingModal');
        const bookingMessage = document.getElementById('bookingMessage');
        const paymentInstructions = document.getElementById('paymentInstructions');
        
        const amount = urlParams.get('amount');
        const phone = '0794411286'; // Your payment number
        
        bookingMessage.textContent = `Your booking has been received successfully. Please complete payment to secure your space.`;
        
        // Payment instructions based on device
        const paymentCode = `*182*1*1*${phone}*${amount}#`;
        if (/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            paymentInstructions.innerHTML = `
                <p>To complete payment:</p>
                <ol>
                    <li>Open your phone dialer</li>
                    <li>Dial: <strong>${paymentCode}</strong></li>
                    <li>Press call</li>
                    <li>Follow the prompts to complete payment</li>
                </ol>
                <a href="tel:${paymentCode}" class="btn">Dial Payment Code</a>
            `;
        } else {
            paymentInstructions.innerHTML = `
                <p>To complete payment from your mobile phone:</p>
                <ol>
                    <li>Open your phone dialer</li>
                    <li>Dial: <strong>${paymentCode}</strong></li>
                    <li>Press call</li>
                    <li>Follow the prompts to complete payment</li>
                </ol>
            `;
        }
        
        bookingModal.style.display = 'block';
        
        // Remove the parameter from URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    // Show application success modal if URL has application_success parameter
    if (urlParams.has('application_success')) {
        const applicationModal = document.getElementById('applicationModal');
        applicationModal.style.display = 'block';
        
        // Remove the parameter from URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    // Close modals
    const closeModals = document.querySelectorAll('.close-modal');
    closeModals.forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
    
    // Form validation for booking
    const bookingForms = document.querySelectorAll('.booking-form-inner');
    bookingForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const phoneInput = this.querySelector('input[name="phone"]');
            const phoneRegex = /^[0-9]{10,15}$/;
            
            if (!phoneRegex.test(phoneInput.value)) {
                e.preventDefault();
                alert('Please enter a valid phone number (10-15 digits)');
                phoneInput.focus();
            }
        });
    });
    
    // Form validation for job applications
    const applicationForms = document.querySelectorAll('.application-form-inner');
    applicationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const phoneInput = this.querySelector('input[name="phone"]');
            const nationalIdInput = this.querySelector('input[name="national_id"]');
            const cvInput = this.querySelector('input[name="cv"]');
            const phoneRegex = /^[0-9]{10,15}$/;
            const nationalIdRegex = /^[0-9]{16}$/;
            
            let isValid = true;
            
            if (!phoneRegex.test(phoneInput.value)) {
                isValid = false;
                alert('Please enter a valid phone number (10-15 digits)');
                phoneInput.focus();
            } else if (nationalIdInput.value && !nationalIdRegex.test(nationalIdInput.value)) {
                isValid = false;
                alert('National ID must be 16 digits');
                nationalIdInput.focus();
            } else if (cvInput.files[0]) {
                const fileName = cvInput.files[0].name;
                const fileExt = fileName.split('.').pop().toLowerCase();
                
                if (fileExt !== 'pdf') {
                    isValid = false;
                    alert('Please upload a PDF file for your CV');
                    cvInput.focus();
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    
    // Header scroll effect
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.header');
        if (window.scrollY > 100) {
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        }
    });
});
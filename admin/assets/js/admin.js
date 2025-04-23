document.addEventListener('DOMContentLoaded', function() {
    // Room Modals
    const viewRoomButtons = document.querySelectorAll('.view-room');
    const editRoomButtons = document.querySelectorAll('.edit-room');
    const roomModal = document.getElementById('roomModal');
    const editRoomModal = document.getElementById('editRoomModal');
    const closeModals = document.querySelectorAll('.close-modal');
    
    // View Room Details
    viewRoomButtons.forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-id');
            fetch(`includes/get_room.php?id=${roomId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('roomModalContent').innerHTML = data;
                    roomModal.style.display = 'block';
                });
        });
    });
    
    // Edit Room Form
    editRoomButtons.forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-id');
            fetch(`includes/get_room.php?id=${roomId}&edit=1`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editRoomId').value = data.id;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editSize').value = data.size;
                    document.getElementById('editWidth').value = data.width;
                    document.getElementById('editHeight').value = data.height;
                    document.getElementById('editFloor').value = data.floor;
                    document.getElementById('editPrice').value = data.price;
                    document.getElementById('editDiscountPrice').value = data.discount_price;
                    document.getElementById('editDescription').value = data.description;
                    document.getElementById('editFeatures').value = data.features;
                    document.getElementById('editStatus').value = data.status;
                    
                    // Display current image if exists
                    const imageContainer = document.getElementById('currentImageContainer');
                    imageContainer.innerHTML = '';
                    if (data.image) {
                        const img = document.createElement('img');
                        img.src = `../uploads/${data.image}`;
                        img.style.maxWidth = '200px';
                        img.style.maxHeight = '150px';
                        imageContainer.appendChild(img);
                    } else {
                        imageContainer.innerHTML = '<p>No image uploaded</p>';
                    }
                    
                    editRoomModal.style.display = 'block';
                });
        });
    });
    
    // Booking Modals
    const viewBookingButtons = document.querySelectorAll('.view-booking');
    const bookingModal = document.getElementById('bookingModal');
    
    viewBookingButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-id');
            fetch(`includes/get_booking.php?id=${bookingId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('bookingModalContent').innerHTML = data;
                    bookingModal.style.display = 'block';
                });
        });
    });
    
    // Delete Booking
    const deleteBookingButtons = document.querySelectorAll('.delete-booking');
    deleteBookingButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this booking?')) {
                fetch(`includes/delete_booking.php?id=${bookingId}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('tr').remove();
                    } else {
                        alert('Error deleting booking');
                    }
                });
            }
        });
    });
    
    // Job Modals
    const viewJobButtons = document.querySelectorAll('.view-job');
    const editJobButtons = document.querySelectorAll('.edit-job');
    const jobModal = document.getElementById('jobModal');
    const editJobModal = document.getElementById('editJobModal');
    
    viewJobButtons.forEach(button => {
        button.addEventListener('click', function() {
            const jobId = this.getAttribute('data-id');
            fetch(`includes/get_job.php?id=${jobId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('jobModalContent').innerHTML = data;
                    jobModal.style.display = 'block';
                });
        });
    });
    
    editJobButtons.forEach(button => {
        button.addEventListener('click', function() {
            const jobId = this.getAttribute('data-id');
            fetch(`includes/get_job.php?id=${jobId}&edit=1`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editJobId').value = data.id;
                    document.getElementById('editJobTitle').value = data.title;
                    document.getElementById('editJobDescription').value = data.description;
                    document.getElementById('editJobDeadline').value = data.deadline;
                    document.getElementById('editJobGender').value = data.gender_requirement;
                    document.getElementById('editJobActive').checked = data.is_active == 1;
                    
                    editJobModal.style.display = 'block';
                });
        });
    });
    
    // Applicant Modal
    const viewApplicantButtons = document.querySelectorAll('.view-applicant');
    const applicantModal = document.getElementById('applicantModal');
    
    viewApplicantButtons.forEach(button => {
        button.addEventListener('click', function() {
            const applicantId = this.getAttribute('data-id');
            fetch(`includes/get_applicant.php?id=${applicantId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('applicantModalContent').innerHTML = data;
                    applicantModal.style.display = 'block';
                });
        });
    });
    
    // Close Modals
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
    
    // Search functionality
    const roomSearch = document.getElementById('roomSearch');
    const bookingSearch = document.getElementById('bookingSearch');
    const jobSearch = document.getElementById('jobSearch');
    const applicantSearch = document.getElementById('applicantSearch');
    
    if (roomSearch) {
        roomSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#roomsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    
    if (bookingSearch) {
        bookingSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#bookingsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    
    if (jobSearch) {
        jobSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#jobsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    
    if (applicantSearch) {
        applicantSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#applicantsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});
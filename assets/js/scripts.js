// Form validation for registration
function validateRegistrationForm(form) {
    const email = form.email.value.trim();
    const password = form.password.value;
    const confirmPassword = form.confirm_password.value;
    
    // Email validation
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        showError('Please enter a valid email address.');
        return false;
    }
    
    // Password validation
    if (password.length < 8) {
        showError('Password must be at least 8 characters long.');
        return false;
    }
    
    if (password !== confirmPassword) {
        showError('Passwords do not match.');
        return false;
    }
    
    showLoading(form);
    return true;
}

// Form validation for booking
function validateBookingForm(form) {
    const date = new Date(form.booking_date.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Date validation
    if (date < today) {
        showError('Please select a future date.');
        return false;
    }
    
    // Quantity validation for tickets
    if (form.quantity) {
        const quantity = parseInt(form.quantity.value);
        if (quantity < 1 || quantity > 10) {
            showError('Please select between 1 and 10 tickets.');
            return false;
        }
    }
    
    showLoading(form);
    return true;
}

// Show loading state
function showLoading(form) {
    form.classList.add('loading');
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.dataset.originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
    }
}

// Remove loading state
function hideLoading(form) {
    form.classList.remove('loading');
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn && submitBtn.dataset.originalText) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = submitBtn.dataset.originalText;
    }
}

// Show error message
function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger alert-dismissible fade show';
    errorDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(errorDiv, container.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

// Initialize Bootstrap components
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Add scrolled class to navbar on scroll
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
    
    // Add animation to cards on scroll
    const cards = document.querySelectorAll('.card');
    if ('IntersectionObserver' in window) {
        const cardObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1 }
        );
        
        cards.forEach(card => cardObserver.observe(card));
    }
    
    // Handle form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            if (!form.classList.contains('loading')) {
                showLoading(form);
            }
        });
    });
});

// Quiz form enhancements
document.addEventListener('DOMContentLoaded', function() {
    const quizForm = document.querySelector('.quiz-form');
    if (quizForm) {
        const radioInputs = quizForm.querySelectorAll('input[type="radio"]');
        radioInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Remove active class from all items
                quizForm.querySelectorAll('.list-group-item').forEach(item => {
                    item.classList.remove('active', 'bg-light');
                });
                
                // Add active class to selected item
                this.closest('.list-group-item').classList.add('active', 'bg-light');
            });
        });
    }
});

// Theme Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }
    }

    // Theme toggle button click handler
    const themeToggle = document.getElementById('toggleContrast');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            if (newTheme === 'dark') {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }
        });
    }
}); 
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
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Processing...
    `;
    
    return () => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    };
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
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    // Theme toggle button click handler
    const themeToggle = document.getElementById('toggleContrast');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        });
    }
});

// Reading Mode Toggle
function toggleReadingMode() {
    document.body.classList.toggle('reading-mode');
    const isReadingMode = document.body.classList.contains('reading-mode');
    
    // Store preference
    localStorage.setItem('readingMode', isReadingMode ? 'enabled' : 'disabled');
    
    // Update ARIA state
    document.body.setAttribute('aria-reading-mode', isReadingMode.toString());
    
    // Track accessibility usage
    trackKPI('accessibility_feature', {
        feature: 'reading_mode',
        state: isReadingMode ? 'enabled' : 'disabled'
    });

    // Announce change to screen readers
    announceToScreenReader(`Reading mode ${isReadingMode ? 'enabled' : 'disabled'}`);
}

// High Contrast Toggle
function toggleHighContrast() {
    document.body.classList.toggle('high-contrast');
    const isHighContrast = document.body.classList.contains('high-contrast');
    
    // Store preference
    localStorage.setItem('highContrast', isHighContrast ? 'enabled' : 'disabled');
    
    // Update ARIA state
    document.body.setAttribute('aria-high-contrast', isHighContrast.toString());
    
    // Track accessibility usage
    trackKPI('accessibility_feature', {
        feature: 'high_contrast',
        state: isHighContrast ? 'enabled' : 'disabled'
    });

    // Announce change to screen readers
    announceToScreenReader(`High contrast mode ${isHighContrast ? 'enabled' : 'disabled'}`);
}

// Screen reader announcements
function announceToScreenReader(message) {
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.setAttribute('class', 'sr-only');
    announcement.textContent = message;
    document.body.appendChild(announcement);
    setTimeout(() => announcement.remove(), 1000);
}

// Initialize accessibility preferences on page load
document.addEventListener('DOMContentLoaded', () => {
    // Restore reading mode preference
    if (localStorage.getItem('readingMode') === 'enabled') {
        document.body.classList.add('reading-mode');
    }
    
    // Restore high contrast preference
    if (localStorage.getItem('highContrast') === 'enabled') {
        document.body.classList.add('high-contrast');
    }
});

// Enhanced theme toggle with accessibility tracking
function applyTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    localStorage.setItem('theme', theme);
    
    // Update ARIA attributes
    document.documentElement.setAttribute('aria-theme', theme);
    
    // Track theme change
    trackKPI('accessibility_feature', {
        feature: 'theme',
        value: theme
    });
    
    // Force refresh problematic elements
    refreshUIElements();
}

// Helper function to refresh UI elements
function refreshUIElements() {
    const elements = document.querySelectorAll('.card, .booking-information, .total-amount');
    elements.forEach(el => {
        el.style.display = 'none';
        el.offsetHeight; // Trigger reflow
        el.style.display = '';
    });
} 
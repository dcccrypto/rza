    </div><!-- End container from header -->
    
    <footer class="bg-success text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-white">Riget Zoo Adventures</h5>
                    <p>Experience wildlife, luxury stays, and educational adventures all in one place.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="text-white">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/rza/bookings/tickets.php" class="text-white text-decoration-none hover-opacity">Book Tickets</a></li>
                        <li><a href="/rza/bookings/hotel.php" class="text-white text-decoration-none hover-opacity">Hotel Booking</a></li>
                        <li><a href="/rza/education/resources.php" class="text-white text-decoration-none hover-opacity">Educational Resources</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-white">Accessibility Options</h5>
                    <div class="d-flex flex-column gap-2">
                        <button class="btn btn-outline-light hover-opacity" onclick="toggleFontSize()" aria-label="Toggle Font Size">
                            <i class="bi bi-fonts"></i> Text Size
                        </button>
                        <button class="btn btn-outline-light hover-opacity" onclick="toggleHighContrast()" aria-label="Toggle High Contrast">
                            <i class="bi bi-circle-half"></i> High Contrast
                        </button>
                        <button class="btn btn-outline-light hover-opacity" onclick="toggleReadingMode()" aria-label="Toggle Reading Mode">
                            <i class="bi bi-book"></i> Reading Mode
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
    .hover-opacity {
        transition: opacity 0.3s ease;
    }
    .hover-opacity:hover {
        opacity: 0.8;
    }
    footer a:hover {
        opacity: 0.8;
    }
    </style>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="/rza/assets/js/scripts.js"></script>
    
    <!-- Accessibility JavaScript -->
    <script>
        // Dark mode toggle
        function toggleDarkMode() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Font size toggle
        function toggleFontSize() {
            const body = document.body;
            const currentSize = window.getComputedStyle(body).fontSize;
            const size = parseFloat(currentSize);
            
            if (size <= 16) {
                body.style.fontSize = '18px';
            } else if (size <= 18) {
                body.style.fontSize = '20px';
            } else {
                body.style.fontSize = '16px';
            }
        }

        // High contrast toggle
        function toggleContrast() {
            document.body.classList.toggle('high-contrast');
        }

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        });
    </script>
</body>
</html> 
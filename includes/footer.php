    </div><!-- End container from header -->
    
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Riget Zoo Adventures</h5>
                    <p>Experience wildlife, luxury stays, and educational adventures all in one place.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/rza/bookings/tickets.php">Book Tickets</a></li>
                        <li><a href="/rza/bookings/hotel.php">Hotel Booking</a></li>
                        <li><a href="/rza/education/resources.php">Educational Resources</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Accessibility</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-light" onclick="toggleFontSize()">
                            <i class="bi bi-fonts"></i> Toggle Font Size
                        </button>
                        <button id="toggleContrast" class="btn btn-outline-light">
                            <i class="bi bi-circle-half"></i> Toggle Theme
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>

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
            localStorage.setItem('darkMode', newTheme === 'dark' ? 'enabled' : 'disabled');
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
    </script>
</body>
</html> 
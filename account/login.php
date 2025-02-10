<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';

// Debug - remove in production
error_log("Login page accessed");

// If already logged in, redirect to profile
if (is_logged_in()) {
    header('Location: profile.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_form();
    
    try {
        // Use prepared statements instead of real_escape_string
        $stmt = $conn->prepare("SELECT id, password FROM aweb_users WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($_POST['password'], $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['last_activity'] = time();
            
            // Update last login time
            update_last_login($user['id']);
            
            error_log("Login successful for user ID: " . $user['id']); // Debug
            
            // Redirect to intended page or profile
            $redirect = $_SESSION['redirect_after_login'] ?? 'profile.php';
            unset($_SESSION['redirect_after_login']);
            
            set_flash_message('Welcome back!', 'success');
            header("Location: $redirect");
            exit;
        } else {
            error_log("Login failed - invalid credentials"); // Debug
            set_flash_message('Invalid email or password.', 'danger');
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        set_flash_message('An error occurred. Please try again.', 'danger');
    }
}

// Include header after all potential redirects
require_once '../includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="login-card">
                <div class="card-body">
                    <h2 class="card-title">Welcome Back</h2>
                    
                    <form method="POST" action="" class="login-form needs-validation" novalidate>
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">Email address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                                       required
                                       autocomplete="email">
                                <div class="invalid-feedback">
                                    Please enter a valid email address
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       required
                                       autocomplete="current-password">
                                <div class="invalid-feedback">
                                    Please enter your password
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <span class="btn-text">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </span>
                        </button>
                    </form>
                    
                    <a href="register.php" class="register-link">
                        Don't have an account? <span class="text-success">Register here</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    if (!this.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
    }
    this.classList.add('was-validated');
});
</script>

<?php require_once '../includes/footer.php'; ?> 
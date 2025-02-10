<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';

// Debug - remove in production
error_log("Register page accessed");

// If already logged in, redirect to profile
if (is_logged_in()) {
    header('Location: profile.php');
    exit;
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_form();
    
    try {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        error_log("Registration attempt for email: " . $email); // Debug
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address.');
        }
        // Validate password length
        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long.');
        }
        // Validate password match
        if ($password !== $confirm_password) {
            throw new Exception('Passwords do not match.');
        }
        
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM aweb_users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            throw new Exception('Email already registered. Please login instead.');
        }
        
        // Start transaction
        $conn->beginTransaction();
        
        // Create new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO aweb_users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $hashed_password]);
        
        $user_id = $conn->lastInsertId();
        
        // Initialize loyalty points
        $stmt = $conn->prepare("INSERT INTO aweb_loyalty_points (user_id, points) VALUES (?, 0)");
        $stmt->execute([$user_id]);
        
        // Commit transaction
        $conn->commit();
        
        error_log("Registration successful for user ID: " . $user_id); // Debug
        
        set_flash_message('Registration successful! Please login.', 'success');
        header('Location: login.php');
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction if active
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        
        error_log("Registration error: " . $e->getMessage());
        set_flash_message($e->getMessage(), 'danger');
    }
}

// Include header after all potential redirects
require_once '../includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm mt-5">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Create Account</h2>
                    
                    <form method="POST" action="" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   minlength="8" required>
                            <div class="form-text">
                                Password must be at least 8 characters long.
                            </div>
                            <div class="invalid-feedback">
                                Please enter a password (minimum 8 characters).
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required>
                            <div class="invalid-feedback">
                                Please confirm your password.
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-person-plus me-2"></i>Register
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p class="mb-0">Already have an account? 
                            <a href="login.php" class="text-success">Login here</a>
                        </p>
                    </div>
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
    
    // Check if passwords match
    const password = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    
    if (password.value !== confirm.value) {
        confirm.setCustomValidity('Passwords do not match');
        e.preventDefault();
    } else {
        confirm.setCustomValidity('');
    }
    
    this.classList.add('was-validated');
});

// Clear custom validity on input
document.getElementById('confirm_password').addEventListener('input', function() {
    this.setCustomValidity('');
});
</script>

<?php require_once '../includes/footer.php'; ?> 
<?php
require_once '../includes/db.php';
require_once '../includes/session_manager.php';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_form();
    
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash_message('Please enter a valid email address.', 'danger');
    }
    // Validate password length
    else if (strlen($password) < 8) {
        set_flash_message('Password must be at least 8 characters long.', 'danger');
    }
    // Validate password match
    else if ($password !== $confirm_password) {
        set_flash_message('Passwords do not match.', 'danger');
    }
    else {
        // Check if email already exists
        $stmt = prepare_stmt("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            set_flash_message('Email already registered. Please login instead.', 'warning');
        } else {
            // Create new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = prepare_stmt("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);
            
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                
                // Initialize loyalty points
                $stmt = prepare_stmt("INSERT INTO loyalty_points (user_id, points) VALUES (?, 0)");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                
                set_flash_message('Registration successful! Please login.', 'success');
                header('Location: login.php');
                exit;
            } else {
                set_flash_message('Registration failed. Please try again.', 'danger');
            }
        }
        $stmt->close();
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
                            <input type="email" class="form-control" id="email" name="email" required>
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

<?php require_once '../includes/footer.php'; ?> 
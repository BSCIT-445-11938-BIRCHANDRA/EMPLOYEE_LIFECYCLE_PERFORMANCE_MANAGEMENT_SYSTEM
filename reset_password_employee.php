<?php include 'components/header.php'; ?>

<div class="page-content">
    <div class="container">
        <div class="login-container">
            <div class="login-box">
                <h2>Reset Password - Employee</h2>
                
                <?php
                // Get token from URL
                $token = $_GET['token'] ?? '';
                
                if (empty($token)) {
                    echo '<div style="background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">Invalid reset link.</div>';
                    echo '<div style="text-align: center; margin-top: 1rem;"><a href="forgot_password_employee.php" style="color: #667eea; text-decoration: none;">Request New Reset Link</a></div>';
                } else {
                    // Include database connection
                    include 'backend/db.php';
                    
                    // Hash token and check if valid
                    $token_hash = hash('sha256', $token);
                    $current_time = date('Y-m-d H:i:s');
                    
                    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_token_expiry > ? AND role = 'employee'");
                    $stmt->bind_param("ss", $token_hash, $current_time);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows === 0) {
                        echo '<div style="background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">Invalid or expired reset link.</div>';
                        echo '<div style="text-align: center; margin-top: 1rem;"><a href="forgot_password_employee.php" style="color: #667eea; text-decoration: none;">Request New Reset Link</a></div>';
                    } else {
                        $user = $result->fetch_assoc();
                        
                        // Display messages
                        if (isset($_GET['success'])) {
                            echo '<div style="background: #d4edda; color: #155724; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">Password reset successfully! You can now login with your new password.</div>';
                        }
                        
                        if (isset($_GET['error'])) {
                            $error_message = '';
                            switch($_GET['error']) {
                                case 'empty':
                                    $error_message = 'Please fill in all fields.';
                                    break;
                                case 'mismatch':
                                    $error_message = 'Passwords do not match.';
                                    break;
                                case 'short':
                                    $error_message = 'Password must be at least 6 characters long.';
                                    break;
                                case 'update_failed':
                                    $error_message = 'Failed to update password. Please try again.';
                                    break;
                                default:
                                    $error_message = 'An error occurred. Please try again.';
                            }
                            echo '<div style="background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">' . htmlspecialchars($error_message) . '</div>';
                        }
                        ?>
                        
                        <form action="backend/reset_password_employee_process.php" method="POST" id="resetPasswordForm">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            
                            <div class="form-group">
                                <label for="password">New Password *</label>
                                <input type="password" id="password" name="password" required placeholder="Enter new password" minlength="6">
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password *</label>
                                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm new password" minlength="6">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                            
                            <div style="text-align: center; margin-top: 1rem;">
                                <a href="login_employee.php" style="color: #667eea; text-decoration: none;">Back to Login</a>
                            </div>
                        </form>
                        <?php
                    }
                    
                    $stmt->close();
                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    // Get form values
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Simple validation
    if (!password || !confirmPassword) {
        e.preventDefault();
        alert('Please fill in all fields.');
        return;
    }
    
    // Password length validation
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long.');
        return;
    }
    
    // Password match validation
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match.');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.textContent = 'Resetting...';
    submitBtn.disabled = true;
});
</script>

<?php include 'components/footer.php'; ?>

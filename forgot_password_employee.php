<?php include 'components/header.php'; ?>

<div class="page-content">
    <div class="container">
        <div class="login-container">
            <div class="login-box">
                <h2>Forgot Password - Employee</h2>
                
                <?php
                // Display messages
                if (isset($_GET['success'])) {
                    echo '<div style="background: #d4edda; color: #155724; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">Password reset instructions have been sent to your email.</div>';
                }
                
                if (isset($_GET['error'])) {
                    $error_message = '';
                    switch($_GET['error']) {
                        case 'empty':
                            $error_message = 'Please enter your email address.';
                            break;
                        case 'invalid':
                            $error_message = 'Invalid email address.';
                            break;
                        case 'not_found':
                            $error_message = 'No account found with this email address.';
                            break;
                        case 'email_failed':
                            $error_message = 'Failed to send reset email. Please try again.';
                            break;
                        default:
                            $error_message = 'An error occurred. Please try again.';
                    }
                    echo '<div style="background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">' . htmlspecialchars($error_message) . '</div>';
                }
                ?>
                
                <form action="backend/forgot_password_employee_process.php" method="POST" id="forgotPasswordForm">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your registered email address">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    
                    <div style="text-align: center; margin-top: 1rem;">
                        <a href="login_employee.php" style="color: #667eea; text-decoration: none;">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    // Get form values
    const email = document.getElementById('email').value;
    
    // Simple validation
    if (!email) {
        e.preventDefault();
        alert('Please enter your email address.');
        return;
    }
    
    // Email validation
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.textContent = 'Sending...';
    submitBtn.disabled = true;
});
</script>

<?php include 'components/footer.php'; ?>

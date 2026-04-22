<?php include 'components/header.php'; ?>

<div class="page-content">
    <div class="container">
        <div class="login-container">
            <div class="login-box">
                <h2>Employee Login</h2>
                
                <?php
                // Display error messages
                if (isset($_GET['error'])) {
                    $error_message = '';
                    switch($_GET['error']) {
                        case 'empty':
                            $error_message = 'Please fill in all fields.';
                            break;
                        case 'invalid':
                            $error_message = 'Invalid email or password.';
                            break;
                        case 'unauthorized':
                            $error_message = 'Please login to access this page.';
                            break;
                        case 'access_denied':
                            $error_message = 'Access denied. Employee privileges required.';
                            break;
                        default:
                            $error_message = 'Login failed. Please try again.';
                    }
                    echo '<div style="background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">' . htmlspecialchars($error_message) . '</div>';
                }
                
                // Display logout success message
                if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
                    echo '<div style="background: #d4edda; color: #155724; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">You have been successfully logged out.</div>';
                }
                ?>
                
                <form action="backend/login_employee_process_fixed.php" method="POST" id="employeeLoginForm">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your employee email">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required placeholder="Enter your password">
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" id="remember" name="remember" style="width: auto;">
                            Remember me
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-secondary">Login</button>
                    
                    <div style="text-align: center; margin-top: 1rem;">
                        <a href="login_admin.php" style="color: #667eea; text-decoration: none;">Admin Login?</a>
                    </div>
                    
                    <div style="text-align: center; margin-top: 0.5rem;">
                        <a href="forgot_password_employee.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('employeeLoginForm').addEventListener('submit', function(e) {
    // Get form values
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // Simple validation
    if (!email || !password) {
        e.preventDefault();
        alert('Please enter both email and password.');
        return;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }
    
    // Password validation (minimum 6 characters)
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long.');
        return;
    }
    
    // Show loading message
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Logging in...';
    submitBtn.disabled = true;
    
    // Allow form to submit to backend
    // The loading state will remain until redirect happens
});
</script>

<?php include 'components/footer.php'; ?>

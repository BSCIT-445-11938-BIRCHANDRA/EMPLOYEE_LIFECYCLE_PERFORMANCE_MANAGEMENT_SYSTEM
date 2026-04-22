<?php
/**
 * Email System Test Script
 * This script tests the email notification functionality
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include email configuration
require_once 'backend/email_config.php';

// Test results
$test_results = [];

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Email System Test - EMS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        .test-section { margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .test-section h2 { color: #555; margin-bottom: 15px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; }
        .warning { color: #ffc107; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .result { margin-top: 15px; padding: 10px; border-radius: 5px; }
        .result.success { background: #d4edda; border: 1px solid #c3e6cb; }
        .result.error { background: #f8d7da; border: 1px solid #f5c6cb; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📧 Email System Test</h1>
        <p class='info'>This page tests the email notification functionality of the Employee Management System.</p>";

// Handle test form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_type = $_POST['test_type'] ?? '';
    $test_email = $_POST['test_email'] ?? '';
    
    if (empty($test_email) || !filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='result error'>Please provide a valid email address for testing.</div>";
    } else {
        switch ($test_type) {
            case 'basic':
                $result = testEmailConfig($test_email);
                if ($result['success']) {
                    echo "<div class='result success'>✅ Basic email test successful! Message sent to: $test_email</div>";
                } else {
                    echo "<div class='result error'>❌ Basic email test failed: " . htmlspecialchars($result['message']) . "</div>";
                }
                break;
                
            case 'task_assignment':
                $result = sendTaskAssignmentEmail(
                    $test_email,
                    'Test Employee',
                    'Test Task Assignment',
                    'This is a test task to verify email notifications are working properly.',
                    date('Y-m-d', strtotime('+7 days'))
                );
                if ($result) {
                    echo "<div class='result success'>✅ Task assignment email sent successfully to: $test_email</div>";
                } else {
                    echo "<div class='result error'>❌ Task assignment email failed to send</div>";
                }
                break;
                
            case 'leave_approved':
                $result = sendLeaveStatusEmail(
                    $test_email,
                    'Test Employee',
                    'Annual Leave',
                    date('Y-m-d'),
                    date('Y-m-d', strtotime('+3 days')),
                    'approved'
                );
                if ($result) {
                    echo "<div class='result success'>✅ Leave approval email sent successfully to: $test_email</div>";
                } else {
                    echo "<div class='result error'>❌ Leave approval email failed to send</div>";
                }
                break;
                
            case 'leave_rejected':
                $result = sendLeaveStatusEmail(
                    $test_email,
                    'Test Employee',
                    'Sick Leave',
                    date('Y-m-d'),
                    date('Y-m-d', strtotime('+1 day')),
                    'rejected',
                    'Insufficient leave balance'
                );
                if ($result) {
                    echo "<div class='result success'>✅ Leave rejection email sent successfully to: $test_email</div>";
                } else {
                    echo "<div class='result error'>❌ Leave rejection email failed to send</div>";
                }
                break;
        }
    }
}

// Display current email configuration
global $email_config;
echo "
        <div class='test-section'>
            <h2>Current Email Configuration</h2>
            <pre><strong>SMTP Host:</strong> " . htmlspecialchars($email_config['host']) . "
<strong>SMTP Port:</strong> " . htmlspecialchars($email_config['port']) . "
<strong>SMTP Security:</strong> " . htmlspecialchars($email_config['smtp_secure']) . "
<strong>Username:</strong> " . htmlspecialchars($email_config['username']) . "
<strong>From Email:</strong> " . htmlspecialchars($email_config['from_email']) . "
<strong>From Name:</strong> " . htmlspecialchars($email_config['from_name']) . "</pre>
        </div>

        <div class='test-section'>
            <h2>Test Email Functions</h2>
            <form method='POST' action=''>
                <div class='form-group'>
                    <label for='test_email'>Test Email Address:</label>
                    <input type='email' id='test_email' name='test_email' placeholder='Enter your email address' required>
                </div>
                
                <div class='form-group'>
                    <label>Test Type:</label>
                    <button type='submit' name='test_type' value='basic' class='btn'>Test Basic Email</button>
                    <button type='submit' name='test_type' value='task_assignment' class='btn'>Test Task Assignment</button>
                    <button type='submit' name='test_type' value='leave_approved' class='btn'>Test Leave Approval</button>
                    <button type='submit' name='test_type' value='leave_rejected' class='btn'>Test Leave Rejection</button>
                </div>
            </form>
        </div>

        <div class='test-section'>
            <h2>📋 Email Features Implemented</h2>
            <ul>
                <li class='success'>✅ PHPMailer library setup completed</li>
                <li class='success'>✅ Email configuration and helper functions created</li>
                <li class='success'>✅ Task assignment email notifications integrated</li>
                <li class='success'>✅ Leave approval/rejection email notifications integrated</li>
                <li class='success'>✅ Email settings configuration page created</li>
                <li class='info'>ℹ️ Email settings added to admin sidebar menu</li>
            </ul>
        </div>

        <div class='test-section'>
            <h2>🔧 Setup Instructions</h2>
            <ol>
                <li><strong>Configure Gmail SMTP:</strong>
                    <ul>
                        <li>Enable 2-Step Verification in your Google Account</li>
                        <li>Go to Google Account → Security → App Passwords</li>
                        <li>Generate a new App Password for this application</li>
                        <li>Use the App Password (not your regular password)</li>
                    </ul>
                </li>
                <li><strong>Update Email Settings:</strong>
                    <ul>
                        <li>Go to Admin Panel → Email Settings</li>
                        <li>Enter your Gmail credentials</li>
                        <li>Test the configuration</li>
                    </ul>
                </li>
                <li><strong>Test Email Functions:</strong>
                    <ul>
                        <li>Use the test form above to verify each email type</li>
                        <li>Check your inbox and spam folder</li>
                        <li>Verify email content and formatting</li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class='test-section'>
            <h2>⚠️ Common Issues & Solutions</h2>
            <div class='warning'>
                <strong>Issue:</strong> SMTP connect failed<br>
                <strong>Solution:</strong> Check internet connection, verify SMTP settings, ensure correct port
            </div><br>
            <div class='warning'>
                <strong>Issue:</strong> Authentication failed<br>
                <strong>Solution:</strong> Use Google App Password (not regular password), check 2-Step Verification
            </div><br>
            <div class='warning'>
                <strong>Issue:</strong> Emails going to spam<br>
                <strong>Solution:</strong> Check SPF/DNS records, verify sender domain
            </div><br>
            <div class='warning'>
                <strong>Issue:</strong> TLS/SSL errors<br>
                <strong>Solution:</strong> Try different security settings (TLS/SSL/None), check OpenSSL extension
            </div>
        </div>

        <div class='test-section'>
            <h2>🚀 Next Steps</h2>
            <ul>
                <li>Configure your email settings in the admin panel</li>
                <li>Test all email functions using the form above</li>
                <li>Assign a test task to verify task assignment emails</li>
                <li>Submit and approve/reject a test leave request</li>
                <li>Monitor email logs for any delivery issues</li>
            </ul>
        </div>

        <div style='text-align: center; margin-top: 30px;'>
            <a href='login_admin.php' class='btn'>Go to Admin Login</a> |
            <a href='frontend/admin/email_settings.php' class='btn'>Email Settings</a>
        </div>
    </div>
</body>
</html>";
?>

<?php
// Test database connection and users
echo "<h2>Database Connection Test</h2>";

try {
    // Include database connection
    include("backend/db.php");
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Check if users table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'users'");
    if ($table_check->num_rows > 0) {
        echo "<p style='color: green;'>✅ Users table exists!</p>";
        
        // Count total users
        $count_result = $conn->query("SELECT COUNT(*) as total FROM users");
        $total_users = $count_result->fetch_assoc()['total'];
        echo "<p>Total users in database: <strong>$total_users</strong></p>";
        
        // Show admin users
        $admin_result = $conn->query("SELECT id, name, email, role FROM users WHERE role = 'admin'");
        echo "<h3>Admin Users:</h3>";
        if ($admin_result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
            while ($user = $admin_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>❌ No admin users found!</p>";
        }
        
        // Show employee users
        $employee_result = $conn->query("SELECT id, name, email, role FROM users WHERE role = 'employee'");
        echo "<h3>Employee Users:</h3>";
        if ($employee_result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
            while ($user = $employee_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ No employee users found!</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Users table does not exist!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Test Login Credentials:</h3>";
echo "<p>If you have users in the database, try these credentials to test login:</p>";
echo "<ul>";
echo "<li><strong>Admin Login:</strong> Use any email from admin users above with the corresponding password</li>";
echo "<li><strong>Employee Login:</strong> Use any email from employee users above with the corresponding password</li>";
echo "</ul>";

echo "<hr>";
echo "<p><a href='login_admin.php'>Go to Admin Login</a> | <a href='login_employee.php'>Go to Employee Login</a></p>";
?>

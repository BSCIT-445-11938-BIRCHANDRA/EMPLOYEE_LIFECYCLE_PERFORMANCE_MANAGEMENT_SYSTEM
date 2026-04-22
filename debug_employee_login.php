<?php
// Employee Login Debug Script
echo "<h1>🔍 EMPLOYEE LOGIN DEBUG</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
    .debug-card { background: white; padding: 25px; margin: 15px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .section-title { color: #2c3e50; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 15px; }
    .test-item { padding: 12px; margin: 10px 0; border-left: 4px solid #667eea; background: #f8f9fa; border-radius: 5px; }
    .success { color: #27ae60; font-weight: bold; }
    .error { color: #e74c3c; font-weight: bold; }
    .warning { color: #f39c12; font-weight: bold; }
    .info { color: #3498db; }
    .code { background: #e9ecef; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
    .btn { background: #667eea; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
    .btn-success { background: #27ae60; }
    .btn-danger { background: #e74c3c; }
    .form-test { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin: 10px 0; }
    .form-test input { padding: 10px; margin: 5px; border: 1px solid #ddd; border-radius: 3px; width: 300px; }
</style>";

echo "<div class='debug-card'>";
echo "<h2 class='section-title'>📁 EMPLOYEE LOGIN FILES CHECK</h2>";

// Check login files
$login_files = [
    'Employee Login Page' => 'login_employee.php',
    'Employee Login Process' => 'backend/login_employee_process.php',
    'Database Connection' => 'backend/db.php'
];

foreach ($login_files as $name => $file) {
    echo "<div class='test-item'>";
    echo "<strong>$name:</strong> <span class='code'>$file</span><br>";
    
    if (file_exists($file)) {
        echo "<span class='success'>✅ File exists</span><br>";
        
        // Check file content
        $content = file_get_contents($file);
        
        if ($name === 'Employee Login Process') {
            if (strpos($content, "role = 'employee'") !== false) {
                echo "<span class='success'>✅ Employee role check found</span><br>";
            } else {
                echo "<span class='error'>❌ Employee role check missing</span><br>";
            }
            
            if (strpos($content, 'frontend/employee/dashboard.php') !== false) {
                echo "<span class='success'>✅ Correct redirect path</span><br>";
            } else {
                echo "<span class='error'>❌ Wrong redirect path</span><br>";
            }
        }
        
    } else {
        echo "<span class='error'>❌ File missing</span><br>";
    }
    echo "</div>";
}

echo "</div>";

// Database Check
echo "<div class='debug-card'>";
echo "<h2 class='section-title'>🗄️ DATABASE EMPLOYEE CHECK</h2>";

try {
    include("backend/db.php");
    echo "<div class='test-item success'>✅ Database connected successfully</div>";
    
    // Check employee users
    $stmt = $conn->prepare("SELECT id, name, email, password, role, status FROM users WHERE role = 'employee'");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div class='test-item success'>✅ Found " . $result->num_rows . " employee users</div>";
        
        echo "<div class='test-item info'><strong>Employee Details:</strong></div>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='test-item'>";
            echo "ID: " . $row['id'] . " | ";
            echo "Name: " . $row['name'] . " | ";
            echo "Email: " . $row['email'] . " | ";
            echo "Password: " . $row['password'] . " | ";
            echo "Status: " . $row['status'];
            echo "</div>";
        }
    } else {
        echo "<div class='test-item error'>❌ NO EMPLOYEE USERS FOUND!</div>";
        echo "<div class='test-item warning'>⚠️ Need to insert employee users</div>";
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='test-item error'>❌ Database error: " . $e->getMessage() . "</div>";
}

echo "</div>";

// Live Login Test
echo "<div class='debug-card'>";
echo "<h2 class='section-title'>🧪 LIVE EMPLOYEE LOGIN TEST</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_employee_login'])) {
    echo "<div class='test-item info'>🔄 Testing employee login...</div>";
    
    $email = $_POST['test_email'] ?? '';
    $password = $_POST['test_password'] ?? '';
    
    echo "<div class='test-item info'>";
    echo "📧 Email: <span class='code'>$email</span><br>";
    echo "🔐 Password: <span class='code'>$password</span><br>";
    echo "</div>";
    
    try {
        include("backend/db.php");
        
        // Test the exact query from login process
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ? AND role = 'employee'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        echo "<div class='test-item info'>";
        echo "📊 Query executed successfully<br>";
        echo "📊 Found rows: " . $result->num_rows . "<br>";
        echo "</div>";
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            echo "<div class='test-item info'>";
            echo "👤 User found: " . $user['name'] . "<br>";
            echo "📧 Email: " . $user['email'] . "<br>";
            echo "🔐 DB Password: " . $user['password'] . "<br>";
            echo "🔐 Input Password: " . $password . "<br>";
            echo "🔍 Password Match: " . (($password === $user['password']) ? '✅ YES' : '❌ NO') . "<br>";
            echo "</div>";
            
            if ($password === $user['password']) {
                echo "<div class='test-item success'>✅ Password verification SUCCESSFUL!</div>";
                
                // Set session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = 'employee';
                
                echo "<div class='test-item success'>✅ Session variables set</div>";
                echo "<div class='test-item info'>🔗 <a href='frontend/employee/dashboard.php' target='_blank' class='btn btn-success'>Go to Employee Dashboard</a></div>";
                
            } else {
                echo "<div class='test-item error'>❌ Password verification FAILED!</div>";
                echo "<div class='test-item warning'>⚠️ Check password in database</div>";
            }
        } else {
            echo "<div class='test-item error'>❌ No employee user found with email: $email</div>";
            echo "<div class='test-item warning'>⚠️ Check if employee exists in database</div>";
        }
        
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        echo "<div class='test-item error'>❌ Login test failed: " . $e->getMessage() . "</div>";
    }
    
} else {
    echo "<div class='form-test'>";
    echo "<h3>🧪 Test Employee Login</h3>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='test_employee_login'>";
    echo "<div>";
    echo "<label>📧 Email:</label><br>";
    echo "<input type='email' name='test_email' value='john@ems.com' required><br><br>";
    echo "</div>";
    echo "<div>";
    echo "<label>🔐 Password:</label><br>";
    echo "<input type='password' name='test_password' value='emp123' required><br><br>";
    echo "</div>";
    echo "<button type='submit' class='btn btn-success'>🧪 Test Employee Login</button>";
    echo "</form>";
    echo "</div>";
}

echo "</div>";

// Common Issues
echo "<div class='debug-card'>";
echo "<h2 class='section-title'>🔧 COMMON EMPLOYEE LOGIN ISSUES</h2>";

echo "<div class='test-item'>";
echo "<strong>Issue 1:</strong> No employee users in database<br>";
echo "<strong>Symptoms:</strong> Login always fails with 'invalid credentials'<br>";
echo "<strong>Fix:</strong> Run database setup to create employee users<br>";
echo "<strong>Command:</strong> <a href='database/quick_setup.php' target='_blank'>database/quick_setup.php</a>";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 2:</strong> Wrong role in query<br>";
echo "<strong>Symptoms:</strong> Employee login fails even with correct credentials<br>";
echo "<strong>Fix:</strong> Ensure query looks for role = 'employee'<br>";
echo "<strong>Check:</strong> <span class='code'>WHERE email = ? AND role = 'employee'</span>";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 3:</strong> Wrong redirect path<br>";
echo "<strong>Symptoms:</strong> Login succeeds but redirects to wrong page<br>";
echo "<strong>Fix:</strong> Update redirect to correct employee dashboard<br>";
echo "<strong>Path:</strong> <span class='code'>../frontend/employee/dashboard.php</span>";
echo "</div>";

echo "<div class='test-item'>";
echo "<strong>Issue 4:</strong> Session not starting<br>";
echo "<strong>Symptoms:</strong> Login redirects back immediately<br>";
echo "<strong>Fix:</strong> Ensure session_start() is called in login process<br>";
echo "<strong>Check:</strong> <span class='code'>session_start();</span> at top of file";
echo "</div>";

echo "</div>";

// Quick Actions
echo "<div class='debug-card'>";
echo "<h2 class='section-title'>🚀 QUICK ACTIONS</h2>";
echo "<div style='text-align: center;'>";
echo "<a href='database/quick_setup.php' target='_blank' class='btn btn-danger'>🗄️ Setup Database</a>";
echo "<a href='login_employee.php' target='_blank' class='btn'>👤 Employee Login</a>";
echo "<a href='backend/login_employee_process.php' target='_blank' class='btn'>🔧 Login Process</a>";
echo "<a href='frontend/employee/dashboard.php' target='_blank' class='btn'>📊 Employee Dashboard</a>";
echo "</div>";

echo "<h3>🔐 Employee Login Credentials:</h3>";
echo "<div class='test-item info'>";
echo "<strong>Employee 1:</strong> john@ems.com / emp123<br>";
echo "<strong>Employee 2:</strong> jane@ems.com / emp123<br>";
echo "<strong>Employee 3:</strong> mike@ems.com / emp123<br>";
echo "<strong>Employee 4:</strong> sarah@ems.com / emp123<br>";
echo "<strong>Employee 5:</strong> tom@ems.com / emp123<br>";
echo "</div>";

echo "</div>";
echo "</div>";
?>

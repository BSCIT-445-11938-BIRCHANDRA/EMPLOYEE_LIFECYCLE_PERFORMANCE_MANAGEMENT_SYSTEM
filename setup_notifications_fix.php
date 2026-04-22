<?php
// Setup notifications table and sample data
include 'backend/db.php';

$conn = new mysqli('localhost', 'root', '', 'ems_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create notifications table if it doesn't exist
$create_table = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'general',
    status ENUM('unread', 'read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_status (user_id, status),
    INDEX idx_created_at (created_at)
)";

if ($conn->query($create_table)) {
    echo "✅ Notifications table created/verified<br>";
    
    // Get all employee IDs
    $employees = $conn->query("SELECT id FROM users WHERE role = 'employee'");
    
    if ($employees->num_rows > 0) {
        // Insert sample notifications for each employee
        $insert = $conn->prepare("INSERT IGNORE INTO notifications (user_id, message, type) VALUES (?, ?, ?)");
        
        $sample_notifications = [
            ["Welcome to the Employee Management System!", "system"],
            ["Your attendance has been marked for today", "attendance"],
            ["You have a new task assigned", "task"],
            ["Don't forget to mark your attendance", "reminder"]
        ];
        
        while ($employee = $employees->fetch_assoc()) {
            foreach ($sample_notifications as $notification) {
                $insert->bind_param("iss", $employee['id'], $notification[0], $notification[1]);
                $insert->execute();
            }
        }
        
        echo "✅ Sample notifications added for all employees<br>";
    }
} else {
    echo "❌ Failed to create notifications table: " . $conn->error . "<br>";
}

$conn->close();

echo "<script>
setTimeout(function() {
    window.location.href = 'frontend/employee/dashboard.php';
}, 2000);
</script>";
?>

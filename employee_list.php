<?php
// Include database connection
include("backend/db.php");

// Query to get all employee emails and passwords
$sql = "SELECT id, name, email, password FROM users WHERE role = 'employee' ORDER BY id";
$result = $conn->query($sql);

echo "EMPLOYEE CREDENTIALS\n";
echo "====================\n\n";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . "\n";
        echo "Name: " . $row["name"] . "\n";
        echo "Email: " . $row["email"] . "\n";
        echo "Password: " . $row["password"] . "\n";
        echo "------------------------\n";
    }
} else {
    echo "No employees found in the database.\n";
}

$conn->close();
?>

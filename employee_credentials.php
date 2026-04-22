<?php
// Include database connection
include("backend/db.php");

// Query to get all employee emails and passwords
$sql = "SELECT id, name, email, password FROM users WHERE role = 'employee' ORDER BY id";
$result = $conn->query($sql);

echo "<h2>Employee Email and Password List</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f2f2f2;'>";
echo "<th>ID</th>";
echo "<th>Name</th>";
echo "<th>Email</th>";
echo "<th>Password</th>";
echo "</tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No employees found</td></tr>";
}

echo "</table>";

$conn->close();
?>

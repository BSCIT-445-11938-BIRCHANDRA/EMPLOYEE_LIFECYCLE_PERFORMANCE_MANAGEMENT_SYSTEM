<?php
include 'backend/db.php';

echo "<h2>Quick Debug - Employee 8 (nyx)</h2>";

// Check attendance table structure
echo "<h3>Attendance Table Structure:</h3>";
$result = $conn->query("DESCRIBE attendance");
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['Field']}</td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "</tr>";
}
echo "</table>";

// Get all records for employee 8
echo "<h3>All Records for Employee 8:</h3>";
$stmt = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ?");
$stmt->bind_param("i", 8);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Employee ID</th><th>Date</th><th>Status</th><th>Check In</th><th>Check Out</th></tr>";

$present_count = 0;
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['employee_id']}</td>";
    echo "<td><strong>{$row['date']}</strong></td>";
    echo "<td><strong>{$row['status']}</strong></td>";
    echo "<td>{$row['check_in']}</td>";
    echo "<td>{$row['check_out']}</td>";
    echo "</tr>";
    
    if ($row['status'] === 'present') {
        $present_count++;
    }
}
echo "</table>";
echo "<h3>Manual Present Count: $present_count</h3>";

// Test our fixed query
$this_month = date('Y-m');
echo "<h3>Testing Fixed Query for $this_month:</h3>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND YEAR(date) = YEAR(?) AND MONTH(date) = MONTH(?)");
$stmt->bind_param("iss", 8, $this_month . '-01', $this_month . '-01');
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];
echo "<p>Fixed Query Result: $count present days</p>";

// Test simpler query
echo "<h3>Testing Simple Query:</h3>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present'");
$stmt->bind_param("i", 8);
$stmt->execute();
$result = $stmt->get_result();
$count_all = $result->fetch_assoc()['count'];
echo "<p>All Time Present: $count_all present days</p>";

$conn->close();
?>

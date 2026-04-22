<?php
include 'backend/db.php';

echo "<h2>Direct Attendance Test for Employee 8 (nyx)</h2>";

// Test 1: Get all present records
echo "<h3>Test 1: All Present Records</h3>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present'");
$stmt->bind_param("i", 8);
$stmt->execute();
$result = $stmt->get_result();
$count1 = $result->fetch_assoc()['count'];
echo "<p>All Present: $count1</p>";

// Test 2: Get this month present records
echo "<h3>Test 2: This Month Present Records</h3>";
$this_month = date('Y-m');
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND DATE_FORMAT(date, '%Y-%m') = ?");
$stmt->bind_param("is", 8, $this_month);
$stmt->execute();
$result = $stmt->get_result();
$count2 = $result->fetch_assoc()['count'];
echo "<p>This Month Present (DATE_FORMAT): $count2</p>";

// Test 3: Get this month present records using YEAR/MONTH
echo "<h3>Test 3: This Month Present Records (YEAR/MONTH)</h3>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND YEAR(date) = YEAR(?) AND MONTH(date) = MONTH(?)");
$year_month = $this_month . '-01';
$stmt->bind_param("iss", 8, $year_month, $year_month);
$stmt->execute();
$result = $stmt->get_result();
$count3 = $result->fetch_assoc()['count'];
echo "<p>This Month Present (YEAR/MONTH): $count3</p>";

// Test 4: Show actual records for this month
echo "<h3>Test 4: Actual Records This Month</h3>";
$stmt = $conn->prepare("SELECT date, status FROM attendance WHERE employee_id = ? AND date LIKE ? ORDER BY date");
$stmt->bind_param("is", 8, $this_month . '%');
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Date</th><th>Status</th></tr>";

$manual_count = 0;
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['date']}</td>";
    echo "<td><strong>{$row['status']}</strong></td>";
    echo "</tr>";
    
    if ($row['status'] === 'present') {
        $manual_count++;
    }
}
echo "</table>";
echo "<p>Manual Count: $manual_count</p>";

echo "<h3>Summary:</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Method</th><th>Result</th></tr>";
echo "<tr><td>All Present</td><td>$count1</td></tr>";
echo "<tr><td>DATE_FORMAT Method</td><td>$count2</td></tr>";
echo "<tr><td>YEAR/MONTH Method</td><td>$count3</td></tr>";
echo "<tr><td>Manual Count</td><td>$manual_count</td></tr>";
echo "</table>";

$conn->close();
?>

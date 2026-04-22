<?php
include 'backend/db.php';

echo "<h2>Complete Attendance Debug for Employee 8 (nyx)</h2>";

// Get employee info
$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->bind_param("i", 8);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
echo "<h3>Employee Info:</h3>";
echo "<p><strong>Name:</strong> " . $employee['name'] . "</p>";
echo "<p><strong>Email:</strong> " . $employee['email'] . "</p>";

// Get ALL attendance for this employee
echo "<h3>All Attendance Records:</h3>";
$stmt = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? ORDER BY date DESC");
$stmt->bind_param("i", 8);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
echo "<tr><th>Date</th><th>Status</th><th>Check In</th><th>Check Out</th><th>Created At</th></tr>";

$total_present = 0;
$all_records = [];
while ($row = $result->fetch_assoc()) {
    $all_records[] = $row;
    echo "<tr>";
    echo "<td>" . $row['date'] . "</td>";
    echo "<td><strong>" . $row['status'] . "</strong></td>";
    echo "<td>" . ($row['check_in'] ?? 'N/A') . "</td>";
    echo "<td>" . ($row['check_out'] ?? 'N/A') . "</td>";
    echo "<td>" . ($row['created_at'] ?? 'N/A') . "</td>";
    echo "</tr>";
    
    if ($row['status'] === 'present') {
        $total_present++;
    }
}
echo "</table>";
echo "<p><strong>Total Present (All Time):</strong> $total_present</p>";

// Test current month with different methods
$this_month = date('Y-m');
echo "<h3>Testing Different Query Methods for $this_month:</h3>";

// Method 1: Original broken method
echo "<h4>Method 1: LIKE (Original)</h4>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND date LIKE ?");
$stmt->bind_param("is", 8, $this_month . '%');
$stmt->execute();
$result = $stmt->get_result();
$count1 = $result->fetch_assoc()['count'];
echo "<p>Result: $count1 present days</p>";

// Method 2: DATE_FORMAT (Our fix)
echo "<h4>Method 2: DATE_FORMAT (Fixed)</h4>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND DATE_FORMAT(date, '%Y-%m') = ?");
$stmt->bind_param("is", 8, $this_month);
$stmt->execute();
$result = $stmt->get_result();
$count2 = $result->fetch_assoc()['count'];
echo "<p>Result: $count2 present days</p>";

// Method 3: YEAR and MONTH functions
echo "<h4>Method 3: YEAR and MONTH functions</h4>";
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND YEAR(date) = YEAR(?) AND MONTH(date) = MONTH(?)");
$stmt->bind_param("iss", 8, $this_month . '-01', $this_month . '-01');
$stmt->execute();
$result = $stmt->get_result();
$count3 = $result->fetch_assoc()['count'];
echo "<p>Result: $count3 present days</p>";

// Method 4: Manual date range
echo "<h4>Method 4: Manual date range</h4>";
$start_date = $this_month . '-01';
$end_date = $this_month . '-31';
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendance WHERE employee_id = ? AND status = 'present' AND date >= ? AND date <= ?");
$stmt->bind_param("iss", 8, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();
$count4 = $result->fetch_assoc()['count'];
echo "<p>Result: $count4 present days</p>";

// Show records for this month specifically
echo "<h3>Records for $this_month:</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
echo "<tr><th>Date</th><th>Status</th><th>Check In</th><th>Check Out</th></tr>";

$month_present = 0;
foreach ($all_records as $row) {
    if (strpos($row['date'], $this_month) === 0) { // Starts with 2026-04
        echo "<tr style='background: #e8f5e8;'>";
        echo "<td>" . $row['date'] . "</td>";
        echo "<td><strong>" . $row['status'] . "</strong></td>";
        echo "<td>" . ($row['check_in'] ?? 'N/A') . "</td>";
        echo "<td>" . ($row['check_out'] ?? 'N/A') . "</td>";
        echo "</tr>";
        
        if ($row['status'] === 'present') {
            $month_present++;
        }
    }
}
echo "</table>";
echo "<p><strong>Manual Count for $this_month:</strong> $month_present present days</p>";

echo "<h3>Summary:</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Method</th><th>Count</th><th>Correct?</th></tr>";
echo "<tr><td>LIKE Method</td><td>$count1</td><td>" . ($count1 == $month_present ? '✅' : '❌') . "</td></tr>";
echo "<tr><td>DATE_FORMAT Method</td><td>$count2</td><td>" . ($count2 == $month_present ? '✅' : '❌') . "</td></tr>";
echo "<tr><td>YEAR/MONTH Method</td><td>$count3</td><td>" . ($count3 == $month_present ? '✅' : '❌') . "</td></tr>";
echo "<tr><td>Date Range Method</td><td>$count4</td><td>" . ($count4 == $month_present ? '✅' : '❌') . "</td></tr>";
echo "<tr><td>Manual Count</td><td>$month_present</td><td>🎯 Reference</td></tr>";
echo "</table>";

$conn->close();
?>

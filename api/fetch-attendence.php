<?php
header("Content-Type: application/json");

// Create a connection to the database
$conn = new mysqli("localhost", "root", "", "payroll");

// Check the connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch all attendance records
$result = $conn->query("SELECT * FROM attendance_tables");

if ($result->num_rows > 0) {
    // If records found, fetch all records as an associative array
    $attendanceData = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(["success" => true, "data" => $attendanceData]);
} else {
    // If no records found
    echo json_encode(["success" => false, "message" => "No attendance records found."]);
}

// Close the database connection
$conn->close();
?>

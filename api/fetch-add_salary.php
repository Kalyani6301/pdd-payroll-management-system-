<?php
header("Content-Type: application/json");

// Database connection
$conn = new mysqli("localhost", "root", "", "payroll");

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// Fetch all salary details
$result = $conn->query("SELECT * FROM salary");

// Convert results to an associative array and return as JSON
echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);

// Close the database connection
$conn->close();
?>

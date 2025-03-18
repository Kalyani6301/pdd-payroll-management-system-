<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'admin/config.php';

header('Content-Type: application/json');

// Check DB Connection
if (!$conn) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Fetch Attendance with Employee Details
$sql = "SELECT 
            a.attendence_date, 
            a.emp_code, 
            a.action_name, 
            a.action_time, 
            a.emp_desc, 
            e.first_name, 
            e.last_name 
        FROM emp_attendence a 
        INNER JOIN employees e ON a.emp_code = e.emp_code
        ORDER BY a.attendence_date DESC";

$result = $conn->query($sql);

if (!$result) {
    die(json_encode(["success" => false, "message" => "Query failed: " . $conn->error]));
}

$attendances = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendances[] = [
            "attendence_date" => $row["attendence_date"],
            "emp_code" => $row["emp_code"],
            "name" => $row["first_name"] . " " . $row["last_name"], // Concatenated Name
            "action_name" => $row["action_name"],
            "action_time" => $row["action_time"],
            "emp_desc" => $row["emp_desc"]
        ];
    }
}

// Return JSON data
echo json_encode(["success" => true, "data" => $attendances]);
?>

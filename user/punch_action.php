<?php

// Include necessary files
include_once __DIR__ . "/conf/config.php";
include_once __DIR__ . "/conf/check_login.php";

session_start();
header('Content-Type: application/json'); // Ensure JSON response

// Read the raw JSON input
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Debugging: Log received data
file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

// Check if data is received correctly
if (!$data || !isset($data["emp_code"], $data["action_name"], $data["action_time"], $data["attendence_date"])) {
    echo json_encode(["success" => false, "message" => "Missing required parameters"]);
    exit();
}

// Assign received values
$emp_code = trim($data["emp_code"]);
$action_name = trim($data["action_name"]);
$action_time = trim($data["action_time"]);
$attendance_date = trim($data["attendence_date"]); // Correct date format
$emp_desc = trim($data["emp_desc"] ?? "");
$latitude = trim($data["latitude"] ?? "");
$longitude = trim($data["longitude"] ?? "");

// Insert Punch Record with attendance_date
$stmt = $conn->prepare("INSERT INTO emp_attendence (emp_code, action_name, action_time, attendence_date, emp_desc, latitude, longitude) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssd", $emp_code, $action_name, $action_time, $attendance_date, $emp_desc, $latitude, $longitude);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Punch recorded successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to record punch"]);
}

// Close connection
$stmt->close();
$conn->close();

?>

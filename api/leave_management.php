<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "payroll");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Get POST data
$action = $_POST['action'] ?? '';
$leave_id = $_POST['leave_id'] ?? null;
$status = $_POST['status'] ?? null;

if ($action == "update_status") {
    if (!$leave_id || !$status) {
        echo json_encode(["status" => "error", "message" => "Invalid request data"]);
        exit;
    }

    // Convert status to match database ENUM ('approve', 'reject')
    $status = strtolower($status) === 'approved' ? 'approve' : 'reject';

    // Update leave status in database
    $stmt = $conn->prepare("UPDATE leave_request SET leave_status = ? WHERE leave_id = ?");
    $stmt->bind_param("si", $status, $leave_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Leave status updated successfully!", "updated_status" => ucfirst($status)]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update leave status"]);
    }

    $stmt->close();
}

$conn->close();
?>

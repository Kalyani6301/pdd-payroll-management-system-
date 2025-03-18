<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "payroll");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Handle JSON input properly
$input = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? $input['action'] ?? '';

if ($action == 'fetch') {
    $result = $conn->query("SELECT * FROM payhead");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    exit;
}

if ($action == 'save' || $action == 'update') {
    $payhead_id = intval($input['payhead_id'] ?? 0);
    $title = htmlspecialchars(trim($input['payhead_title'] ?? ''));
    $desc = htmlspecialchars(trim($input['payhead_desc'] ?? ''));
    $type = htmlspecialchars(trim($input['payhead_type'] ?? ''));

    if (empty($title) || empty($desc) || empty($type)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if ($action == 'update' && $payhead_id > 0) {
        $stmt = $conn->prepare("UPDATE payhead SET payhead_title=?, payhead_desc=?, payhead_type=? WHERE payhead_id=?");
        $stmt->bind_param("sssi", $title, $desc, $type, $payhead_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO payhead (payhead_title, payhead_desc, payhead_type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $desc, $type);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Payhead saved successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }
    exit;
}

if ($action == 'delete') {
    $payhead_id = intval($input['payhead_id'] ?? 0);

    if ($payhead_id > 0) {
        $stmt = $conn->prepare("DELETE FROM payhead WHERE payhead_id=?");
        $stmt->bind_param("i", $payhead_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Payhead deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete payhead."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid Payhead ID."]);
    }
    exit;
}
?>

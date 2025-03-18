<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "payroll");
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$action = $_REQUEST['action'] ?? '';

if ($action == 'fetch') {
    $result = $conn->query("SELECT holiday_id, holiday_title, holiday_desc, holiday_date, holiday_type FROM holidays ORDER BY holiday_date ASC");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    exit;
}

if ($action == 'get_holiday') {
    $holiday_id = $_GET['holiday_id'] ?? '';
    if ($holiday_id) {
        $stmt = $conn->prepare("SELECT * FROM holidays WHERE holiday_id = ?");
        $stmt->bind_param("i", $holiday_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $holiday = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $holiday]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid holiday ID"]);
    }
    exit;
}

if ($action == 'save' || $action == 'update') {
    $holiday_id = $_POST['holiday_id'] ?? '';
    $holiday_title = $_POST['holiday_title'] ?? '';
    $holiday_desc = $_POST['holiday_desc'] ?? '';
    $holiday_date = $_POST['holiday_date'] ?? '';
    $holiday_type = $_POST['holiday_type'] ?? '';

    if (empty($holiday_title) || empty($holiday_desc) || empty($holiday_date) || empty($holiday_type)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit;
    }

    if (!empty($holiday_id)) {
        $stmt = $conn->prepare("UPDATE holidays SET holiday_title=?, holiday_desc=?, holiday_date=?, holiday_type=? WHERE holiday_id=?");
        $stmt->bind_param("ssssi", $holiday_title, $holiday_desc, $holiday_date, $holiday_type, $holiday_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO holidays (holiday_title, holiday_desc, holiday_date, holiday_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $holiday_title, $holiday_desc, $holiday_date, $holiday_type);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
    exit;
}

if ($action == 'delete') {
    $holiday_id = $_POST['holiday_id'] ?? '';
    if ($holiday_id) {
        $stmt = $conn->prepare("DELETE FROM holidays WHERE holiday_id=?");
        $stmt->bind_param("i", $holiday_id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid holiday ID"]);
    }
    exit;
}
?>

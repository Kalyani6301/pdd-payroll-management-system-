<?php
header("Content-Type: application/json");
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "payroll";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Ensure session user_id is set
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(["success" => false, "message" => "Unauthorized: User not logged in."]));
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        http_response_code(400);
        die(json_encode(["success" => false, "message" => "Invalid request data."]));
    }

    if (isset($data['punchInTime'])) {
        $punchInTime = $data['punchInTime'];
        
        // Insert Punch In Time
        $sql = "INSERT INTO attendance_tables (user_id, punch_in_time) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("is", $userId, $punchInTime);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Punch In recorded."]);
            } else {
                http_response_code(500);
                echo json_encode(["success" => false, "message" => "Error recording Punch In."]);
            }
            $stmt->close();
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Database error: Failed to prepare statement."]);
        }
    }

    if (isset($data['punchOutTime'])) {
        $punchOutTime = $data['punchOutTime'];
        
        // Update Punch Out Time for the latest record with a NULL punch_out_time
        $sql = "UPDATE attendance_tables SET punch_out_time = ? WHERE user_id = ? AND punch_out_time IS NULL ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $punchOutTime, $userId);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Punch Out recorded."]);
            } else {
                http_response_code(500);
                echo json_encode(["success" => false, "message" => "Error recording Punch Out."]);
            }
            $stmt->close();
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Database error: Failed to prepare statement."]);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>

<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "payroll");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data['emp_code']) && isset($data['emp_password'])) {
        $emp_code = $conn->real_escape_string($data['emp_code']);
        $emp_password = $conn->real_escape_string($data['emp_password']);

        // Check if employee code exists
        $sql = "SELECT emp_id, emp_password FROM employees WHERE emp_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $emp_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Plain text password comparison (not recommended for production)
            if ($emp_password === $user['emp_password']) {
                echo json_encode(["success" => true, "message" => "Login successful.", "redirect" => "../user/emp_dashboard.php"]);
            } else {
                echo json_encode(["success" => false, "message" => "Invalid employee code or password."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Employee code not found."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Employee code and password are required."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method. Use POST."]);
}

$conn->close();
?>

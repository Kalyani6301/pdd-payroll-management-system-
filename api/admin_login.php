<?php
header("Content-Type: application/json");
session_start();

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "payroll";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Check if the request method is POST and if it's a JSON request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true); // Parse JSON data

    // Sanitize and validate input
    $admin_code = trim($data['admin_code'] ?? '');
    $admin_password = trim($data['admin_password'] ?? '');

    if (empty($admin_code) || empty($admin_password)) {
        http_response_code(400);
        die(json_encode(["success" => false, "message" => "Admin code and password are required."]));
    }

    // Prepare SQL query to check the admin in the users table
    $sql = "SELECT admin_id, admin_password FROM users WHERE admin_code = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        die(json_encode(["success" => false, "message" => "Database error: Failed to prepare statement."]));
    }

    $stmt->bind_param("s", $admin_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if (!$admin) {
        http_response_code(401);
        die(json_encode(["success" => false, "message" => "Admin code not found in database."]));
    }

    // Check password (assuming passwords are stored in plain text)
    if ($admin_password === $admin['admin_password']) {
        // Store admin data in session
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_code'] = $admin_code;

        // Respond with success message and redirect URL
        echo json_encode(["success" => true, "message" => "Login successful.", "redirect" => "Admin.php"]);
    } else {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Incorrect password."]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method or content type."]);
}

$conn->close();
?>

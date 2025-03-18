<?php
session_start();
header("Content-Type: application/json"); // Ensure JSON response

$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "payroll"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = trim($_POST["employee_id"]);
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $identity = trim($_POST["identity"]);
    $contact = trim($_POST["contact"]);
    $dob = trim($_POST["dob"]);
    $joining = trim($_POST["joining"]);
    $bloodgroup = trim($_POST["bloodgroup"]);
    $employee_type = trim($_POST["employee_type"]);

    // Validate fields
    if (empty($employee_id) || empty($name) || empty($email) || empty($identity) || empty($contact) || empty($dob) || empty($joining) || empty($bloodgroup) || empty($employee_type)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }
    if (!is_numeric($contact) || strlen($contact) < 10) {
        echo json_encode(["success" => false, "message" => "Invalid contact number."]);
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT employee_id FROM employees WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Error: Email already exists."]);
        exit;
    }
    $stmt->close();

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO employees (employee_id, name, email, identity, contact, dob, joining, bloodgroup, employee_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $employee_id, $name, $email, $identity, $contact, $dob, $joining, $bloodgroup, $employee_type);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Employee registration successful!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>

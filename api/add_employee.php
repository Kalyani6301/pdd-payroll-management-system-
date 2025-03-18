<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "employee_db";

// Establishing a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieving form data
$emp_code = $_POST['emp_code'];
$emp_name = $_POST['emp_name'];
$designation = $_POST['designation'];
$gender = $_POST['gender'];
$location = $_POST['location'];
$department = $_POST['department'];
$date_of_joining = $_POST['date_of_joining'];
$bank_name = $_POST['bank_name'];
$bank_account = $_POST['bank_account'];
$ifsc_code = $_POST['ifsc_code'];
$pan = $_POST['pan_no'];
$pf_account = $_POST['pf_account'];

// Prepare and execute the SQL query
$sql = "INSERT INTO employees (
    emp_code, emp_name, designation, gender, location, department, date_of_joining, 
    bank_name, bank_account, ifsc_code, pan, pf_account
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssssss", 
    $emp_code, $emp_name, $designation, $gender, $location, 
    $department, $date_of_joining, $bank_name, $bank_account, 
    $ifsc_code, $pan, $pf_account
);

if ($stmt->execute()) {
    echo "<div class='text-success'>Employee details have been successfully saved.</div>";
} else {
    echo "<div class='text-danger'>Error: " . $stmt->error . "</div>";
}

// Close connection
$stmt->close();
$conn->close();
?>

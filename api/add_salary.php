<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "payroll";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$employee_code = $_POST['employee_code'] ?? '';
$employee_name = $_POST['employee_name'] ?? '';
$designation = $_POST['designation'] ?? '';
$gender = $_POST['gender'] ?? '';
$location = $_POST['location'] ?? '';
$department = $_POST['department'] ?? '';
$joining_date = $_POST['joining_date'] ?? '';
$bank_name = $_POST['bank_name'] ?? '';
$bank_account = $_POST['bank_account'] ?? '';
$ifsc_code = $_POST['ifsc_code'] ?? '';
$pan = $_POST['pan'] ?? '';
$pf_account = $_POST['pf_account'] ?? '';
$working_days = $_POST['working_days'] ?? '';
$leaves = $_POST['leaves'] ?? '';
$travel_expenses = $_POST['travel_expenses'] ?? 0;
$deductions = $_POST['deductions'] ?? 0;
$net_salary = $travel_expenses - $deductions;

$sql = "INSERT INTO salary (employee_code, employee_name, designation, gender, location, department, joining_date, bank_name, bank_account, ifsc_code, pan, pf_account, working_days, leaves, travel_expenses, deductions, net_salary)
VALUES ('$employee_code', '$employee_name', '$designation', '$gender', '$location', '$department', '$joining_date', '$bank_name', '$bank_account', '$ifsc_code', '$pan', '$pf_account', '$working_days', '$leaves', '$travel_expenses', '$deductions', '$net_salary')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Salary details saved!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

$conn->close();
?>

<?php
include 'config.php';
header("Content-Type: application/json"); // Ensure JSON response
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ["status" => "error", "message" => "Unknown error occurred"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_code = $_POST['employee_code'] ?? '';
    $salary = $_POST['salary'] ?? 0;
    $earnings = $_POST['earnings'] ?? 0;
    $deductions = $_POST['deductions'] ?? 0;
    $net_salary = $_POST['net_salary'] ?? 0;
    $earning_type = $_POST['earning_type'] ?? '';
    $deduction_type = $_POST['deduction_type'] ?? '';
    $pay_month = $_POST['pay_month'] ?? '';
    $generate_date = $_POST['generate_date'] ?? '';

    if (empty($emp_code) || empty($pay_month) || empty($generate_date)) {
        $response["message"] = "Missing required fields";
        echo json_encode($response);
        exit;
    }

    $sql = "INSERT INTO salary_table 
            (emp_code, pay_amount, earning_total, deduction_total, net_salary, generate_date, earning_type, deduction_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        $response["message"] = "SQL error: " . $conn->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("sddddsss", $emp_code, $salary, $earnings, $deductions, $net_salary, $generate_date, $earning_type, $deduction_type);

    if ($stmt->execute()) {
        $response["status"] = "success";
        $response["message"] = "Payslip saved successfully";
    } else {
        $response["message"] = "Error saving payslip: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>

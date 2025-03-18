<?php
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $emp_code = $_POST['emp_code'];
    $payheads = $_POST['payheads'];

    if (empty($emp_code) || empty($payheads)) {
        echo json_encode(["success" => false, "message" => "Invalid data."]);
        exit();
    }

    foreach ($payheads as $payhead) {
        $payhead_id = $payhead['id'];
        $default_salary = $payhead['amount'];

        // Check if entry already exists
        $query = "SELECT salary_id FROM pay_structure WHERE emp_code = ? AND payhead_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $emp_code, $payhead_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $update = "UPDATE pay_structure SET default_salary = ? WHERE emp_code = ? AND payhead_id = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("dsi", $default_salary, $emp_code, $payhead_id);
        } else {
            $insert = "INSERT INTO pay_structure (emp_code, payhead_id, default_salary) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("sid", $emp_code, $payhead_id, $default_salary);
        }

        $stmt->execute();
    }

    echo json_encode(["success" => true, "message" => "Pay structure updated successfully."]);
}
?>

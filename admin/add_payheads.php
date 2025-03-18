<?php
include 'config.php';

$emp_code = $_POST['emp_code'];
$payheads = $_POST['payheads'];

foreach ($payheads as $payhead) {
    $payhead_id = $payhead['id'];
    $amount = $payhead['amount'];

    $sql = "INSERT INTO employee_payheads (emp_code, payhead_id, amount) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE amount=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidi", $emp_code, $payhead_id, $amount, $amount);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true, "message" => "Pay heads added successfully"]);
?>

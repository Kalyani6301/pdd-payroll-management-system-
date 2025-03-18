<?php
session_start();
include 'config.php'; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['emp_code'])) {
    echo "Unauthorized access!";
    exit;
}

$emp_code = $_SESSION['emp_code']; // Get logged-in employee code

// Fetch employee details along with salary info
$sql = "SELECT s.salary_id, s.emp_code, e.first_name, e.last_name, s.pay_amount, 
        s.earning_total, s.deduction_total, s.net_salary, s.generate_date, 
        s.earning_type, s.deduction_type 
        FROM salary_table s 
        JOIN employees e ON s.emp_code = e.emp_code 
        WHERE s.emp_code = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $emp_code);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Salary Details</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .download-icon { color: green; cursor: pointer; font-size: 20px; }
        .download-icon:hover { color: darkgreen; }
        .hidden { display: none; }
        .print-section { padding: 20px; border: 1px solid #ddd; margin-top: 20px; }
        .download-icon {
    color: white; /* White text */
    background-color: green; /* Green background */
    padding: 8px 12px; /* Adjust padding for better visibility */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Adjust font size */
    font-weight: bold; /* Bold text */
    text-transform: uppercase; /* Uppercase text */
    cursor: pointer; /* Show pointer cursor on hover */
    border: none; /* Remove border */
    transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
    display: inline-block; /* Make it inline but respect padding */
}

.download-icon:hover {
    background-color: darkgreen; /* Darker green on hover */
    transform: scale(1.1); /* Slightly enlarge on hover */
}

.download-icon:active {
    background-color: limegreen; /* Light green on click */
    transform: scale(1); /* Restore size on click */
}

    </style>
</head>
<body>

<h2>My Salary Details</h2>
<table>
    <thead>
        <tr>
            <th>Pay Amount</th>
            <th>Earnings</th>
            <th>Deductions</th>
            <th>Net Salary</th>
            <th>Generate Date</th>
            <th>Earning Type</th>
            <th>Deduction Type</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= number_format($row['pay_amount'], 2) ?></td>
            <td><?= number_format($row['earning_total'], 2) ?></td>
            <td><?= number_format($row['deduction_total'], 2) ?></td>
            <td><?= number_format($row['net_salary'], 2) ?></td>
            <td><?= htmlspecialchars($row['generate_date']) ?></td>
            <td><?= htmlspecialchars($row['earning_type']) ?></td>
            <td><?= htmlspecialchars($row['deduction_type']) ?></td>
            <td>
                <span class="download-icon" onclick="printSalary(
                    '<?= $row['first_name'] ?>', 
                    '<?= $row['last_name'] ?>', 
                    '<?= $row['pay_amount'] ?>', 
                    '<?= $row['earning_total'] ?>', 
                    '<?= $row['deduction_total'] ?>', 
                    '<?= $row['net_salary'] ?>', 
                    '<?= $row['generate_date'] ?>', 
                    '<?= $row['earning_type'] ?>', 
                    '<?= $row['deduction_type'] ?>'
                )">print</span>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Printable Section -->
<div id="printableArea" class="hidden">
    <div class="print-section">
        <h2>Salary Slip</h2>
        <p><strong>Employee Name:</strong> <span id="emp_name"></span></p>
        <p><strong>Pay Amount:</strong> ₹<span id="pay_amount"></span></p>
        <p><strong>Earnings:</strong> ₹<span id="earnings"></span></p>
        <p><strong>Deductions:</strong> ₹<span id="deductions"></span></p>
        <p><strong>Net Salary:</strong> ₹<span id="net_salary"></span></p>
        <p><strong>Generate Date:</strong> <span id="generate_date"></span></p>
        <p><strong>Earning Type:</strong> <span id="earning_type"></span></p>
        <p><strong>Deduction Type:</strong> <span id="deduction_type"></span></p>
        <p>Thank you,</p>
        <p><strong>HR Department</strong></p>
    </div>
</div>

<script>
function printSalary(firstName, lastName, payAmount, earnings, deductions, netSalary, generateDate, earningType, deductionType) {
    document.getElementById("emp_name").innerText = firstName + " " + lastName;
    document.getElementById("pay_amount").innerText = payAmount;
    document.getElementById("earnings").innerText = earnings;
    document.getElementById("deductions").innerText = deductions;
    document.getElementById("net_salary").innerText = netSalary;
    document.getElementById("generate_date").innerText = generateDate;
    document.getElementById("earning_type").innerText = earningType;
    document.getElementById("deduction_type").innerText = deductionType;

    var printContent = document.getElementById("printableArea").innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload(); // Reload page to restore layout
}
</script>

</body>
</html>

<?php $conn->close(); ?>

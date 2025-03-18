<?php
include 'config.php';

// Fetch employee details using emp_code
$emp_code = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
$employee = null;

if ($emp_code) {
    $sql = "SELECT 
                emp_code, CONCAT(first_name, ' ', last_name) AS name, 
                dob, gender, address, city, state, country, email, mobile_number, 
                identity_doc, idetity_no, emp_type, joining_date, 
                blood_group, designation, department, pan_no, bank_name, account_no, 
                ifsc_code, pf_code 
            FROM employees WHERE emp_code = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $emp_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch deduction options
$deductions = [];
$deduction_query = "SELECT payhead_id, payhead_name FROM payhead WHERE payhead_type = 'Deductions'";
$result_deductions = $conn->query($deduction_query);
while ($row = $result_deductions->fetch_assoc()) {
    $deductions[] = $row;
}

// Fetch earnings options
$earnings = [];
$earnings_query = "SELECT payhead_id, payhead_name FROM payhead WHERE payhead_type = 'Earnings'";
$result_earnings = $conn->query($earnings_query);
while ($row = $result_earnings->fetch_assoc()) {
    $earnings[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h2 class="text-center">Enter Employee Salary Details</h2>

        <?php if ($employee): ?>
        <form id="salaryForm" class="p-4 bg-white shadow rounded">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Employee Code:</label>
                    <input type="text" id="employee_code" name="employee_code" class="form-control" value="<?= htmlspecialchars($employee['emp_code']) ?>" readonly>
                    
                    <label class="form-label">Employee Name:</label>
                    <input type="text" name="employee_name" class="form-control" value="<?= htmlspecialchars($employee['name']) ?>" readonly>
                    
                    <label class="form-label">Designation:</label>
                    <input type="text" name="designation" class="form-control" value="<?= htmlspecialchars($employee['designation']) ?>" readonly>

                    <label class="form-label">Gender:</label>
                    <input type="text" name="gender" class="form-control" value="<?= htmlspecialchars($employee['gender']) ?>" readonly>

                    <label class="form-label">Location:</label>
                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($employee['city'] . ', ' . $employee['state'] . ', ' . $employee['country']) ?>" readonly>

                    <label class="form-label">Department:</label>
                    <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($employee['department']) ?>" readonly>

                    <label class="form-label">Date of Joining:</label>
                    <input type="text" name="joining_date" class="form-control" value="<?= htmlspecialchars($employee['joining_date']) ?>" readonly>


                    <label class="form-label">Salary:</label>
                    <input type="number" id="salary" name="salary" class="form-control" required>
                </div>
                
                <div class="col-md-6">
                <label class="form-label">Bank Name:</label>
                    <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($employee['bank_name']) ?>" readonly>

                    <label class="form-label">Bank Account:</label>
                    <input type="text" name="bank_account" class="form-control" value="<?= htmlspecialchars($employee['account_no']) ?>" readonly>

                    <label class="form-label">IFSC Code:</label>
                    <input type="text" name="ifsc_code" class="form-control" value="<?= htmlspecialchars($employee['ifsc_code']) ?>" readonly>

                    <label class="form-label">PAN:</label>
                    <input type="text" name="pan" class="form-control" value="<?= htmlspecialchars($employee['pan_no']) ?>" readonly>

                    <label class="form-label">PF Account:</label>
                    <input type="text" name="pf_account" class="form-control" value="<?= htmlspecialchars($employee['pf_code']) ?>" readonly>

                  
                    <label class="form-label">Earnings:</label>
                    <select id="earnings" class="form-control">
                        <option value="">Select Earnings</option>
                        <?php foreach ($earnings as $earning): ?>
                            <option value="<?= $earning['payhead_name'] ?>"><?= htmlspecialchars($earning['payhead_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" id="earnings_amount" class="form-control mt-2" placeholder="Enter earnings amount">
                
                    <label class="form-label">Deductions:</label>
                    <select id="deductions" class="form-control">
                        <option value="">Select Deductions</option>
                        <?php foreach ($deductions as $deduction): ?>
                            <option value="<?= $deduction['payhead_name'] ?>"><?= htmlspecialchars($deduction['payhead_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" id="deductions_amount" class="form-control mt-2" placeholder="Enter deductions amount">
                
                    <label class="form-label">Net Salary:</label>
                    <input type="text" id="net_salary" class="form-control" readonly>
                </div>
            </div>
            
            <button type="button" class="btn btn-primary mt-3" onclick="calculateSalary()">Calculate & Save Payslip</button>
            <p id="responseMessage" class="mt-3"></p>
        </form>
        <?php else: ?>
        <div class="alert alert-danger text-center">
            Employee not found. Please go back and try again.
        </div>
        <?php endif; ?>
    </div>

    <script>
        function calculateSalary() {
            let salary = parseFloat(document.getElementById('salary').value) || 0;
            let earnings = parseFloat(document.getElementById('earnings_amount').value) || 0;
            let deductions = parseFloat(document.getElementById('deductions_amount').value) || 0;
            let netSalary = salary + earnings - deductions;
            document.getElementById('net_salary').value = netSalary.toFixed(2);

            // Prepare Data for Database Update
            let formData = new FormData();
            formData.append("employee_code", document.getElementById("employee_code").value);
            formData.append("salary", salary);
            formData.append("earnings", earnings);
            formData.append("deductions", deductions);
            formData.append("net_salary", netSalary);
            formData.append("earning_type", document.getElementById("earnings").value);
            formData.append("deduction_type", document.getElementById("deductions").value);
            formData.append("pay_month", new Date().toISOString().slice(0, 7)); // YYYY-MM
            formData.append("generate_date", new Date().toISOString().split('T')[0]); // YYYY-MM-DD

            fetch("../api/save_salary.php", {
    method: "POST",
    body: formData
})
.then(response => response.text()) // Change from `.json()` to `.text()`
.then(data => {
    console.log("Server Response:", data); // Log the actual response
    try {
        let jsonData = JSON.parse(data);
        document.getElementById("responseMessage").innerHTML = jsonData.message;
    } catch (e) {
        document.getElementById("responseMessage").innerHTML = "Invalid server response.";
    }
})
.catch(error => {
    console.error("Fetch Error:", error);
    document.getElementById("responseMessage").innerHTML = "Network error.";
});

        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

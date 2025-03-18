<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration - Payroll System</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 1000px; /* Set a max-width for better control */
    overflow-y: auto;
    max-height: 90vh;
}

.form-row {
    display: flex;
    flex-wrap: wrap; /* Allow wrapping of items */
    gap: 5px; /* Gap between elements */
}

.input-group {
    flex: 1 1 calc(25% - 10px); /* Four items per row, considering gap */
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
}

.input-group input, .input-group select {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

.btn {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border: none;
    border-radius: 5px;
    background-color: #007BFF;
    color: #fff;
    cursor: pointer;
}

.btn:hover {
    background-color: #0056b3;
}

    </style>
    <script>
        function validateForm() {
            const password = document.querySelector('input[name="emp_password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Employee Registration</h2>
        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="input-group">
                    <label>Employee Code</label>
                    <input type="text" name="emp_code" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="emp_password" required>
                </div>
                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <div class="input-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="input-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" required>
                </div>
                <div class="input-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Marital Status</label>
                    <select name="marital_status" required>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group">
                    <label>Religion</label>
                    <input type="text" name="religion" required>
                </div>
                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="address" required>
                </div>
                <div class="input-group">
                    <label>City</label>
                    <input type="text" name="city" required>
                </div>
                <div class="input-group">
                    <label>State</label>
                    <input type="text" name="state" required>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group">
                    <label>Country</label>
                    <input type="text" name="country" required>
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="input-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile_number" required>
                </div>
                <div class="input-group">
                    <label>Telephone</label>
                    <input type="text" name="telephone" required>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group">
                    <label>Identity Document</label>
                    <input type="text" name="identity_document" required>
                </div>
                <div class="input-group">
                    <label>Identity Number</label>
                    <input type="text" name="identity_number" required>
                </div>
                <div class="input-group">
                    <label>Employee Type</label>
                    <input type="text" name="emp_type" required>
                </div>
                <div class="input-group">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" required>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group">
                    <label>Blood Group</label>
                    <input type="text" name="blood_group" required>
                </div>
                <div class="input-group">
                    <label>Department</label>
                    <input type="text" name="department" required>
                </div>
                <div class="input-group">
                    <label>Designation</label>
                    <input type="text" name="designation" required>
                </div>
                <div class="input-group">
                    <label>PAN Number</label>
                    <input type="text" name="pan_no" required>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" required>
                </div>
                <div class="input-group">
                    <label>Account Number</label>
                    <input type="text" name="account_no" required>
                </div>
                <div class="input-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" required>
                </div>
                <div class="input-group">
                    <label>PF Code</label>
                    <input type="text" name="pf_code" required>
                </div>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = new mysqli('localhost', 'root', '', 'payroll');

            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO employees (emp_code, emp_password, first_name, last_name, dob, gender, marital_status, religion, address, city, state, country, email, mobile_number, telephone, identity_doc, idetity_no, emp_type, joining_date, blood_group, department, designation, pan_no, bank_name, account_no, ifsc_code, pf_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
$stmt->bind_param("sssssssssssssssssssssssssss", $_POST['emp_code'], $_POST['emp_password'], $_POST['first_name'], $_POST['last_name'], $_POST['dob'], $_POST['gender'], $_POST['marital_status'], $_POST['religion'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['country'], $_POST['email'], $_POST['mobile_number'], $_POST['telephone'], $_POST['identity_document'], $_POST['identity_number'], $_POST['emp_type'], $_POST['joining_date'], $_POST['blood_group'], $_POST['department'], $_POST['designation'], $_POST['pan_no'], $_POST['bank_name'], $_POST['account_no'], $_POST['ifsc_code'], $_POST['pf_code']);


            if ($stmt->execute()) {
                echo '<script>alert("Employee registered successfully!");</script>';
            } else {
                echo '<script>alert("Error: ' . $stmt->error . '");</script>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>

<?php include "header.php"; ?>

<div class="wrapper">

<?php include "sidebar.php"; ?>

<div class="main-content">

<?php include "topbar.php"; ?>

    <div class="container cust border p-4 rounded">
        <h3 class="text-center mb-4 mt-5">Employee Details </h3>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $emp_code = $_POST['emp_code'];
            $emp_password = $_POST['emp_password'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $dob = $_POST['dob'];
            $gender = $_POST['gender'];
            $marital_status = $_POST['marital_status'];
            $religion = $_POST['religion'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $country = $_POST['country'];
            $email = $_POST['email'];
            $mobile_number = $_POST['mobile_number'];
            $telephone = $_POST['telephone'];
            $identity_doc = $_POST['identity_doc'];
            $idetity_no = $_POST['idetity_no'];
            $emp_type = $_POST['emp_type'];
            $joining_date = $_POST['joining_date'];
            $blood_group = $_POST['blood_group'];
            $pan_no = $_POST['pan_no'];
            $bank_name = $_POST['bank_name'];
            $account_no = $_POST['account_no'];
            $ifsc_code = $_POST['ifsc_code'];
            $pf_code = $_POST['pf_code'];

            // Handle photo upload
            $photo = "";
            $target_dir = "uploads/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
            }

            if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["name"] != "") {
                $photo = $target_dir . basename($_FILES["photo"]["name"]);
                if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo)) {
                    echo "<div class='alert alert-danger'>Error uploading file. Please check directory permissions.</div>";
                }
            }

            // Database connection
            $conn = new mysqli("localhost", "root", "", "payroll");

            if ($conn->connect_error) {
                die("<div class='alert alert-danger'>Connection failed: " . $conn->connect_error . "</div>");
            }

            $sql = "INSERT INTO employees (emp_code, emp_password, first_name, last_name, dob, gender, marital_status, religion, address, city, state, country, email, mobile_number, telephone, identity_doc, idetity_no, emp_type, joining_date, blood_group, photo, pan_no, bank_name, account_no, ifsc_code, pf_code) VALUES ('$emp_code', '$emp_password', '$first_name', '$last_name', '$dob', '$gender', '$marital_status', '$religion', '$address', '$city', '$state', '$country', '$email', '$mobile_number', '$telephone', '$identity_doc', '$idetity_no', '$emp_type', '$joining_date', '$blood_group', '$photo', '$pan_no', '$bank_name', '$account_no', '$ifsc_code', '$pf_code')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Employee added successfully!');</script>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }

            $conn->close();
        }
        ?>

        <form id="employeeForm" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-4"><label class="form-label">Employee Code:</label><input type="text" class="form-control" name="emp_code" required></div>
                <div class="col-md-4"><label class="form-label">Password:</label><input type="password" class="form-control" name="emp_password" required></div>
                <div class="col-md-4"><label class="form-label">First Name:</label><input type="text" class="form-control" name="first_name" required></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><label class="form-label">Last Name:</label><input type="text" class="form-control" name="last_name" required></div>
                <div class="col-md-4"><label class="form-label">Date of Birth:</label><input type="date" class="form-control" name="dob" required></div>
                <div class="col-md-4"><label class="form-label">Gender:</label><select class="form-control" name="gender" required><option value="">Select Gender</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><label class="form-label">Marital Status:</label><input type="text" class="form-control" name="marital_status" required></div>
                <div class="col-md-4"><label class="form-label">Religion:</label><input type="text" class="form-control" name="religion" required></div>
                <div class="col-md-4"><label class="form-label">Address:</label><input type="text" class="form-control" name="address" required></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><label class="form-label">City:</label><input type="text" class="form-control" name="city" required></div>
                <div class="col-md-4"><label class="form-label">State:</label><input type="text" class="form-control" name="state" required></div>
                <div class="col-md-4"><label class="form-label">Country:</label><input type="text" class="form-control" name="country" required></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="form-label">Email:</label><input type="email" class="form-control" name="email" required></div>
                <div class="col-md-4"><label class="form-label">Mobile Number:</label><input type="text" class="form-control" name="mobile_number" required></div>
                <div class="col-md-4"><label class="form-label">Telephone:</label><input type="text" class="form-control" name="telephone"></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><label class="form-label">Identity Document:</label><input type="text" class="form-control" name="identity_doc" required></div>
                <div class="col-md-4"><label class="form-label">Identity Number:</label><input type="text" class="form-control" name="idetity_no" required></div>
                <div class="col-md-4"><label class="form-label">Employee Type:</label><input type="text" class="form-control" name="emp_type" required></div>
            </div>
           
            <div class="row mb-3">
                <div class="col-md-4"><label class="form-label">PAN Number:</label><input type="text" class="form-control" name="pan_no" required></div>
                 <div class="col-md-4"><label class="form-label">Bank Name:</label><input type="text" class="form-control" name="bank_name" required></div>
                <div class="col-md-4"><label class="form-label">Account Number:</label><input type="text" class="form-control" name="account_no" required></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><label class="form-label">IFSC Code:</label><input type="text" class="form-control" name="ifsc_code" required></div>
                <div class="col-md-4"><label class="form-label">PF Code:</label><input type="text" class="form-control" name="pf_code" required></div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </form>
    </div>

</div>

</div>
<?php include "footer.php"; ?>

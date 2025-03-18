<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: 20px auto;
        }
        .section {
            width: 48%;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h2 {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            color: #fff;
            background-color: #007BFF;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function showSuccessMessage(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <h1 style="text-align: center;">Admin Profile</h1>
    <div class="container">
        <!-- Admin Information Section -->
        <div class="section">
            <h2>Update Profile</h2>
            <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "payroll");

                // Check connection
                if ($conn->connect_error) {
                    die("Database connection failed: " . $conn->connect_error);
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
                    $admin_code = $_POST['admin_code'];
                    $admin_name = $_POST['admin_name'];
                    $admin_email = $_POST['admin_email'];

                    $update_query = "UPDATE users SET admin_code = '$admin_code', admin_name = '$admin_name', admin_email = '$admin_email' WHERE 1";
                    if (mysqli_query($conn, $update_query)) {
                        echo "<script>showSuccessMessage('Profile updated successfully!');</script>";
                    } else {
                        echo "Error updating profile: " . mysqli_error($conn);
                    }
                }

                $query = "SELECT admin_code, admin_name, admin_email FROM users WHERE 1";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
            ?>
            <form action="" method="POST">
                <label>Admin Code:</label>
                <input type="text" name="admin_code" value="<?php echo $row['admin_code']; ?>" required>

                <label>Name:</label>
                <input type="text" name="admin_name" value="<?php echo $row['admin_name']; ?>" required>

                <label>Email:</label>
                <input type="email" name="admin_email" value="<?php echo $row['admin_email']; ?>" required>

                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </div>

        <!-- Change Password Section -->
        <div class="section">
            <h2>Change Password</h2>
            <form action="" method="POST">
                <label>Old Password:</label>
                <input type="password" name="old_password" required>

                <label>New Password:</label>
                <input type="password" name="new_password" required>

                <label>Confirm New Password:</label>
                <input type="password" name="confirm_new_password" required>

                <button type="submit" name="change_password">Change Password</button>
            </form>
        </div>
    </div>
    <?php
        // Change Password Functionality
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            $confirm_new_password = $_POST['confirm_new_password'];

            // Check if the old password is correct
            $check_query = "SELECT * FROM users WHERE admin_password = '$old_password'";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                if ($new_password === $confirm_new_password) {
                    $update_password_query = "UPDATE users SET admin_password = '$new_password' WHERE admin_password = '$old_password'";
                    if (mysqli_query($conn, $update_password_query)) {
                        echo "<script>showSuccessMessage('Password changed successfully!');</script>";
                    } else {
                        echo "Error updating password: " . mysqli_error($conn);
                    }
                } else {
                    echo "<script>alert('New passwords do not match!');</script>";
                }
            } else {
                echo "<script>alert('Old password is incorrect!');</script>";
            }
        }
        // Close connection
        $conn->close();
    ?>
</body>
</html>

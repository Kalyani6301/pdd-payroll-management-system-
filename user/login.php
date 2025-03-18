<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        body {
            background: url('image.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 10px;
        }

        .card-header {
            background: rgba(0, 123, 255, 0.8);
        }
    </style>
<body class="bg-light">
    <?php
    session_start();
    include 'conf/config.php'; // Ensure this path is correct
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $error = ""; // Initialize error variable

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['emp_code']) && !empty($_POST['emp_password'])) {
            $emp_code = $conn->real_escape_string($_POST['emp_code']);
            $emp_password = $conn->real_escape_string($_POST['emp_password']);

            // Check if employee code exists
            $sql = "SELECT emp_code, emp_password FROM employees WHERE emp_code = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $emp_code);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Plain text password comparison (use password_hash in production)
                if ($emp_password === $user['emp_password']) {
                    $_SESSION['emp_code'] = $user['emp_code']; // Store session
                    header("Location: emp_dashboard.php"); // Redirect after login
                    exit();
                } else {
                    $error = "Invalid employee code or password.";
                }
            } else {
                $error = "Employee code not found.";
            }
            $stmt->close();
        } else {
            $error = "Employee code and password are required.";
        }
    }

    // Close database connection safely
    if (isset($conn)) {
        $conn->close();
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Employee Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="emp_code" class="form-label">Employee Code</label>
                                <input type="text" id="emp_code" name="emp_code" class="form-control" placeholder="Enter your Employee Code" required>
                            </div>
                            <div class="mb-3">
                                <label for="emp_password" class="form-label">Password</label>
                                <input type="password" id="emp_password" name="emp_password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            Don't have an account? <a href="../api/register.php">Register here</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

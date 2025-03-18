<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Home Page</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-image: url('../payroll system/user/im.jpg'); /* Add your background image path here */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: center;
        }
        h1 {
            color: white;
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>WELCOME TO PAYROLL MANAGEMENT SYSTEM</h1>
    <div class="container">
        <a href="../payroll system/admin/admin_login.php"> Admin Login</a>
        <a href="../payroll system/user/login.php">Employee Login</a>
    </div>
</body>
</html>

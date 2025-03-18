<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image.jpg'); /* Add your background image path here */
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        input, button {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form id="adminLoginForm">
            <input type="text" name="admin_code" id="admin_code" placeholder="Admin Code" required>
            <input type="password" name="admin_password" id="admin_password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p id="error-message"></p>
    </div>

    <script>
        document.getElementById("adminLoginForm").addEventListener("submit", function (event) {
            event.preventDefault();
            
            const admin_code = document.getElementById("admin_code").value;
            const admin_password = document.getElementById("admin_password").value;
            const errorMessage = document.getElementById("error-message");

            errorMessage.textContent = "";

            const requestData = {
                admin_code: admin_code,
                admin_password: admin_password
            };

            fetch("../api/admin_login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Show success message
                    window.location.href = data.redirect;
                } else {
                    errorMessage.textContent = data.message; // Show error message
                }
            })
            .catch(error => {
                console.error("Error:", error);
                errorMessage.textContent = "An error occurred. Please try again.";
            });
        });
    </script>
</body>
</html>

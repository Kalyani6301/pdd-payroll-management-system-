<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Employee Attendance</h2>
        <div class="mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Punch In Time</th>
                        <th>Punch Out Time</th>
                    </tr>
                </thead>
                <tbody id="attendanceTable">
                    <!-- Attendance records will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript Fetch API -->
    <script>
        // Fetch Attendance Data
        function fetchAttendance() {
            fetch("../api/fetch-attendence.php")  // Adjust the path if necessary
                .then(response => response.json())
                .then(data => {
                    const attendanceTable = document.getElementById("attendanceTable");
                    attendanceTable.innerHTML = ""; // Clear any existing rows

                    if (data.success && data.data.length > 0) {
                        // Loop through each attendance record and add it to the table
                        data.data.forEach(record => {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${record.punch_in_time}</td>
                                <td>${record.punch_out_time || 'Not punched out yet'}</td>
                            `;
                            attendanceTable.appendChild(row);
                        });
                    } else {
                        // If no records found, display a message
                        const row = document.createElement("tr");
                        row.innerHTML = `<td colspan="2" class="text-center">No attendance records found.</td>`;
                        attendanceTable.appendChild(row);
                    }
                })
                .catch(error => {
                    console.error("Error fetching attendance:", error);
                    alert("An error occurred while fetching attendance.");
                });
        }

        // Call the function to load attendance data when the page is loaded
        window.onload = function() {
            fetchAttendance();
        };
    </script>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

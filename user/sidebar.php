<div class="sidebar p-3 d-flex flex-column bg-primary w-25" style="background:rgb(6, 167, 236) !important;position:sticky;margin-top:-60px;box-shadow:0 0 4px 0 rgb(122 120 120);">
    <div class="dropdown mb-3">
        <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
            <div>
                <strong><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></strong>
                <div class="text-success">● Online</div>
            </div>
        </div>
    </div>

    <h6 class="text-uppercase">Navigation</h6>
    <nav class="nav flex-column">
        <a class="nav-link text-white d-flex align-items-center" href="salary_slips.php">
            <i class="fas fa-money-bill-wave me-2"></i> Salary Slips
        </a>
        <a class="nav-link text-white d-flex align-items-center" href="leave.php">
            <i class="fas fa-sign-out-alt me-2"></i> Leaves
        </a>
        <a class="nav-link text-white d-flex align-items-center" href="holidays.php">
            <i class="fas fa-calendar-check me-2"></i> Holidays
        </a>
    </nav>

    <!-- Punch In / Punch Out Section -->
    <div class="mt-4">
        <h6 class="text-uppercase">Punch In / Punch Out</h6>
        <input type="text" id="remarks" class="form-control mb-2" placeholder="Enter remarks (optional)">
        <button id="punchButton" class="btn btn-success w-100" onclick="togglePunch()">Punch In</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
let isPunchedIn = false; // Track Punch State

async function togglePunch() {
    const punchButton = document.getElementById("punchButton");
    const remarks = document.getElementById("remarks").value;
    let emp_code = "<?php echo $_SESSION['emp_code']; ?>"; // Get Employee Code

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async (position) => {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;
            
            let action_name = isPunchedIn ? "Punch Out" : "Punch In";

            // Get current date & time in correct format
            let now = new Date();
            let action_time = now.toLocaleTimeString("en-US",{timeZone: "Asia/Kolkata"}); // Format: HH:MM:SS
            let attendence_date = now.toISOString().split("T")[0]; // Format: YYYY-MM-DD

            try {
                let response = await $.ajax({
                    url: "../user/punch_action.php",
                    method: "POST",
                    contentType: "application/json",
                    dataType: "json",
                    data: JSON.stringify({
                        emp_code: emp_code,
                        action_name: action_name,
                        action_time: action_time,
                        attendence_date: attendence_date,
                        emp_desc: remarks,
                        latitude: latitude,
                        longitude: longitude
                    })
                });

                if (response.success) {
                    alert(`${action_name} Successful!`); // Success Message
                    punchButton.innerText = isPunchedIn ? "Punch In" : "Punch Out";
                    punchButton.classList.toggle("btn-danger", !isPunchedIn);
                    punchButton.classList.toggle("btn-success", isPunchedIn);
                    isPunchedIn = !isPunchedIn; // Toggle state
                } else {
                    alert("Error: " + response.message);
                }

            } catch (error) {
                console.error("AJAX Error:", error);
                alert("Failed to connect to the server. Please check the console.");
            }
        }, (error) => {
            alert("Location access denied. Enable location to use Punch In/Out.");
            console.error("Geolocation error:", error);
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

</script>

<!-- jQuery CDN -->

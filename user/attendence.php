<button id="punchInButton">Punch In</button>

<script>
  let isPunchedIn = false; // Track if the user is punched in or not

  // Function to handle the button click
  document.getElementById("punchInButton").addEventListener("click", function() {
    const userId = 123; // Replace with the actual user ID or session data
    const currentTime = new Date().toISOString(); // Get the current time

    if (!isPunchedIn) {
      // Punch In: Send Punch In time to the server
      fetch("../api/attendence.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          userId: userId,
          punchInTime: currentTime,
        }),
      })
      .then(response => response.json())
      .then(data => {
        alert('Attendance recorded successfully!');
        // Change button text to 'Punch Out'
        document.getElementById("punchInButton").textContent = 'Punch Out';
        isPunchedIn = true; // Mark as punched in
      })
      .catch(error => console.error('Error:', error));
    } else {
      // Punch Out: Send Punch Out time to the server
      fetch("../api/attendence.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          userId: userId,
          punchOutTime: currentTime,
        }),
      })
      .then(response => response.json())
      .then(data => {
        alert('Punch Out recorded successfully!');
        // Change button text back to 'Punch In'
        document.getElementById("punchInButton").textContent = 'Punch In';
        isPunchedIn = false; // Mark as punched out
      })
      .catch(error => console.error('Error:', error));
    }
  });
</script>

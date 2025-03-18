<?php
session_start();
include 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_code = $_POST['emp_code'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $action = $_POST['action']; // punch_in or punch_out
    $current_time = date('Y-m-d H:i:s');
    
    if ($action == 'punch_in') {
        $sql = "INSERT INTO emp_attendence (emp_code, attendence_date, action_name, action_time, emp_desc, latitude, longitude) 
                VALUES ('$emp_code', CURDATE(), 'Punch In', '$current_time', 'User punched in', '$latitude', '$longitude')";
    } elseif ($action == 'punch_out') {
        // Get last punch in time
        $query = "SELECT action_time FROM emp_attendence WHERE emp_code='$emp_code' AND action_name='Punch In' ORDER BY attendence_id DESC LIMIT 1";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $punch_in_time = strtotime($row['action_time']);
            $punch_out_time = strtotime($current_time);
            $work_hours = round(($punch_out_time - $punch_in_time) / 3600, 2);
            
            $sql = "INSERT INTO emp_attendence (emp_code, attendence_date, action_name, action_time, emp_desc, latitude, longitude, work_hours) 
                    VALUES ('$emp_code', CURDATE(), 'Punch Out', '$current_time', 'User punched out', '$latitude', '$longitude', '$work_hours')";
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No punch-in record found']);
            exit;
        }
    }
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => "Successfully recorded $action"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }
}

// Fetch attendance records for admin
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['admin_view'])) {
    $sql = "SELECT emp_code, attendence_date, action_name, action_time, emp_desc, work_hours FROM emp_attendence";
    $result = $conn->query($sql);
    $attendances = [];
    while ($row = $result->fetch_assoc()) {
        $attendances[] = $row;
    }
    echo json_encode($attendances);
}
?>

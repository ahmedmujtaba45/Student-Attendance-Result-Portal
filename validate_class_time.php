<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Karachi');

$teacherID = $_GET['TeacherID'] ?? '';
$courseID = $_GET['CourseID'] ?? '';
$selectedDate = $_GET['Date'] ?? ''; // <--- NEW: Get the date selected by user

if(empty($teacherID) || empty($courseID) || empty($selectedDate)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit();
}

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

// 1. Calculate the Day Name from the USER'S selected date
// strtotime converts "2025-01-04" into "Saturday"
$dayToCheck = date('l', strtotime($selectedDate)); 

// 2. Get the Time to validate
// Note: Usually you validate the CURRENT time against the schedule.
// If you want to allow marking past attendance, you shouldn't validate time range, only Day.
// But assuming you want strict "Live" marking:
$currentTime = date('H:i:s');

// 3. LOGIC: 
// Only allow marking if the Selected Date is TODAY. 
// (Remove this block if you want to allow teachers to mark attendance for past/future dates)
if($selectedDate !== date('Y-m-d')) {
    echo json_encode(['status' => 'error', 'message' => 'You can only mark attendance for the current date.']);
    exit();
}

$sql = "SELECT * FROM timetable 
        WHERE TeacherID = ? 
        AND CourseID = ? 
        AND DayOfWeek = ? 
        AND ? BETWEEN StartTime AND EndTime";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siss", $teacherID, $courseID, $dayToCheck, $currentTime);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success', 
        'message' => "Class Found ($dayToCheck)",
        'slot' => $row['StartTime'] . ' - ' . $row['EndTime']
    ]);
} else {
    // Debugging message to help you see what went wrong
    echo json_encode([
        'status' => 'error', 
        'message' => "No Class Scheduled for $dayToCheck at $currentTime"
    ]);
}

$stmt->close();
$conn->close();
?>
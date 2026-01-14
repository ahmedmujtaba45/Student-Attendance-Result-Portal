<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Karachi'); // Set your Timezone

// 1. Read Input
$input = file_get_contents("php://input");
$payload = json_decode($input, true);

if (!$payload) { exit(json_encode(["status" => "error", "message" => "Invalid Data"])); }

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$type = $payload['type']; 
$courseID = $payload['courseID'];
$teacherID = $payload['teacherID'];
$deptID = ""; 

// Fetch DeptID
$deptQuery = $conn->prepare("SELECT DeptID FROM course WHERE CourseID = ?");
$deptQuery->bind_param("i", $courseID);
$deptQuery->execute();
$res = $deptQuery->get_result();
if($row = $res->fetch_assoc()) { $deptID = $row['DeptID']; }
$deptQuery->close();

if ($type === 'attendance') {
    $date = $payload['date'];
    $data = $payload['data'];
    $currentTime = date("H:i:s"); // GET CURRENT TIMESTAMP

    // UPDATED QUERY: Includes 'Time' column
    // ON DUPLICATE KEY UPDATE: Updates Status AND Time (showing last edit time)
    $stmt = $conn->prepare("
        INSERT INTO attendance (StudentID, CourseID, TeacherID, DeptID, Date, Status, Time) 
        VALUES (?, ?, ?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE Status = VALUES(Status), Time = VALUES(Time)
    ");

    foreach ($data as $record) {
        $stmt->bind_param("sisssss", $record['studentID'], $courseID, $teacherID, $deptID, $date, $record['status'], $currentTime);
        $stmt->execute();
    }
    $stmt->close();
    echo json_encode(["status" => "success", "message" => "Attendance Saved at $currentTime"]);

} elseif ($type === 'result') {
    // Result logic remains the same (Results usually don't need exact timestamp, but you can add it if needed)
    $subject = $payload['subject']; 
    $data = $payload['data']; 

    $stmt = $conn->prepare("
        INSERT INTO results (StudentID, CourseID, TeacherID, DeptID, ExamTitle, Marks, Grade) 
        VALUES (?, ?, ?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE Marks = VALUES(Marks), Grade = VALUES(Grade)
    ");

    foreach ($data as $record) {
        $stmt->bind_param("sisssis", $record['studentID'], $courseID, $teacherID, $deptID, $subject, $record['marks'], $record['grade']);
        $stmt->execute();
    }
    $stmt->close();
    echo json_encode(["status" => "success", "message" => "Results Saved!"]);

} else {
    echo json_encode(["status" => "error", "message" => "Unknown Request Type"]);
}

$conn->close();
?>
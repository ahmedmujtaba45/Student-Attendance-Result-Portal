<?php

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$id = $_GET['id'] ?? null;
$confirm = $_GET['confirm'] ?? 0;

if (!$id) {
    die("No teacher ID provided.");
}

// If not confirmed yet, show confirmation page
if (!$confirm) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Confirm Delete</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='p-5'>
        <div class='container'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'>Warning!</h4>
                <p>Are you sure you want to delete this teacher? This action cannot be undone.</p>
                <hr>
                <a href='delete_teacher.php?id={$id}&confirm=1' class='btn btn-danger'>Yes, Delete</a>
                <a href='AdminDashboard.cshtml' class='btn btn-secondary'>Cancel</a>
            </div>
        </div>
    </body>
    </html>";
    exit;
}
$conn->query("DELETE FROM results WHERE TeacherID='$id'");
$conn->query("DELETE FROM attendance WHERE TeacherID='$id'");
$conn->query("DELETE FROM teachercourse WHERE TeacherID='$id'");
// Confirmed, proceed to delete
$conn->query("DELETE FROM teacher WHERE TeacherID='$id'");

$conn->close();

// Redirect to dashboard with success message
header("Location: admindashboard.cshtml?success=TeacherDeleted");
exit;
?>

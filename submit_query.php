<?php
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$name  = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$query = $_POST['query'];

$sql = "INSERT INTO queries (Name, Email, PhoneNumber, Query)
        VALUES ('$name', '$email', '$phone', '$query')";

if ($conn->query($sql)) {
    echo "<script>
            alert('Your query has been submitted successfully');
            window.location.href='ContactUs.html';
          </script>";
} else {
    echo "Error";
}

$conn->close();
?>

<?php

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$sql = "SELECT * FROM queries ORDER BY CreatedAt DESC";
$res = $conn->query($sql);

echo "<table class='table table-bordered table-striped'>
<tr>
    <th>S.No</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Query</th>
    <th>Date</th>
</tr>";

$serial = 1; // initialize serial number
while ($row = $res->fetch_assoc()) {
    echo "<tr>
            <td>".$serial++."</td>
            <td>{$row['Name']}</td>
            <td>{$row['Email']}</td>
            <td>{$row['PhoneNumber']}</td>
            <td>{$row['Query']}</td>
            <td>{$row['CreatedAt']}</td>
          </tr>";
}

echo "</table>";
$conn->close();
?>

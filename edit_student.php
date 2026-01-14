<?php 
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // UPDATE
    $id = $_POST['StudentID'];
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $pass = $_POST['Password'];

    $sql = "UPDATE student 
            SET Name='$name', Email='$email', Password='$pass' 
            WHERE StudentID='$id'";

    $conn->query($sql);
    header("Location: admindashboard.cshtml");
    exit;

} else {

    // LOAD DATA
    $id = $_GET['id'];
    $res = $conn->query(
        "SELECT * FROM student WHERE StudentID='$id'"
    );
    $row = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<style>
    body {
        background-color: #F5F6FA;
        font-family: Arial, sans-serif;
    }
    .navbar {
        background-color: #0A3D62;
    }
    .navbar-brand {
        color: white !important;
    }
    .card {
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
</style>
</head>

<body>

<nav class="navbar">
    <div class="container">
        <span class="navbar-brand">Edit Student</span>
        <a href="AdminDashboard.cshtml" class="btn btn-light btn-sm">Back</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h4 class="mb-4 text-center">Update Student Information</h4>

                <form method="POST">
                    <input type="hidden" name="StudentID" value="<?= $row['StudentID'] ?>">

                    <label class="form-label">Name</label>
                    <input type="text" class="form-control mb-3"
                           name="Name" value="<?= $row['Name'] ?>" required>

                    <label class="form-label">Email</label>
                    <input type="email" class="form-control mb-3"
                           name="Email" value="<?= $row['Email'] ?>" required>

                    <label class="form-label">Password</label>
                    <input type="text" class="form-control mb-3"
                           name="Password" value="<?= $row['Password'] ?>" required>

                    

                    <button class="btn btn-primary w-100">Save Changes</button>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>

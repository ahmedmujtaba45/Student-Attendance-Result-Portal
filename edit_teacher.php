<?php

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

if ($_POST) {
    $id = $_POST['TeacherID'];
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $department = $_POST['Department'];

    $stmt = $conn->prepare(
        "UPDATE teacher SET Name=?, Email=?, Password=?, Department=? WHERE TeacherID=?"
    );
    $stmt->bind_param("sssss", $name, $email, $password, $department, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admindashboard.cshtml");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM teacher WHERE TeacherID=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Teacher</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
        <span class="navbar-brand">Edit Teacher</span>
        <a href="admindashboard.cshtml" class="btn btn-light btn-sm">Back</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h4 class="mb-4 text-center">Update Teacher Information</h4>

                <form method="POST">
                    <input type="hidden" name="TeacherID" value="<?= $row['TeacherID'] ?>">

                    <label class="form-label">Name</label>
                    <input type="text" class="form-control mb-3"
                           name="Name" value="<?= $row['Name'] ?>" required>

                    <label class="form-label">Email</label>
                    <input type="email" class="form-control mb-3"
                           name="Email" value="<?= $row['Email'] ?>" required>

                    <label class="form-label">Password</label>
                    <input type="text" class="form-control mb-3"
                           name="Password" value="<?= $row['Password'] ?>" required>

                    <label class="form-label">Department</label>
                    <input type="text" class="form-control mb-4"
                           name="Department" value="<?= $row['Department'] ?>" required>

                    <button class="btn btn-primary w-100">Save Changes</button>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
header('Content-Type: application/json');

// Database Connection
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$role = $_POST['role'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$department = $_POST['department'] ?? ''; // Now used for both Teachers and Students

// Helper to map codes to Full Names (Used for both Teacher and Student)
$deptMap = [
    "SE" => "Software Engineering", 
    "CS" => "Computer Science", 
    "BBA" => "Business Admin",
    "PHM" => "Pharmacy", 
    "ENG" => "English", 
    "EE" => "Electrical Engineering", 
    "AI" => "Artificial Intelligence"
];

// Validation
if (empty($role) || empty($name) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit();
}

$newID = "";

try {
    if ($role === 'admin') {
        // --- ADMIN ID GENERATION (ADM001 -> ADM002) ---
        $sql = "SELECT AdminID FROM admin ORDER BY AdminID DESC LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['AdminID']; 
            $num = (int)substr($lastID, 3); 
            $newID = "ADM" . str_pad($num + 1, 3, "0", STR_PAD_LEFT);
        } else {
            $newID = "ADM001";
        }

        $stmt = $conn->prepare("INSERT INTO admin (AdminID, Name, Email, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $newID, $name, $email, $password);
        $stmt->execute();

    } elseif ($role === 'teacher') {
        // --- TEACHER ID GENERATION (T-SE-001 -> T-SE-002) ---
        if (empty($department)) {
            echo json_encode(["status" => "error", "message" => "Department required for Teachers"]);
            exit();
        }

        $deptPrefix = "T-" . $department . "-"; 
        
        $sql = "SELECT TeacherID FROM teacher WHERE TeacherID LIKE '$deptPrefix%' ORDER BY LENGTH(TeacherID) DESC, TeacherID DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['TeacherID']; 
            $num = (int)substr($lastID, strlen($deptPrefix));
            $newID = $deptPrefix . str_pad($num + 1, 3, "0", STR_PAD_LEFT);
        } else {
            $newID = $deptPrefix . "001";
        }

        $fullDeptName = $deptMap[$department] ?? $department;
        $stmt = $conn->prepare("INSERT INTO teacher (TeacherID, Name, Email, Password, Department) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $newID, $name, $email, $password, $fullDeptName);
        $stmt->execute();

    } elseif ($role === 'student') {
        // --- STUDENT ID GENERATION (BSE260001 -> BSE260002) ---
        if (empty($department)) {
            echo json_encode(["status" => "error", "message" => "Department required for Students"]);
            exit();
        }

        // 1. Get Prefix based on Department Map + Current Year
        $year = date("y"); // Returns '26' for 2026
        
        // Map Department Codes to ID Prefixes
        $idPrefixMap = [
            "SE" => "BSE", 
            "CS" => "BCS", 
            "BBA" => "BBA", 
            "PHM" => "DPH", 
            "ENG" => "BSENG", 
            "EE" => "BEE", 
            "AI" => "BAI"
        ];
        
        $prefix = ($idPrefixMap[$department] ?? "STD") . $year; // e.g., BSE26

        // 2. Find last ID for this specific Batch/Dept
        $sql = "SELECT StudentID FROM student WHERE StudentID LIKE '$prefix%' ORDER BY LENGTH(StudentID) DESC, StudentID DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $lastID = $result->fetch_assoc()['StudentID'];
            // Remove prefix to get the number sequence
            $num = (int)substr($lastID, strlen($prefix));
            $newID = $prefix . str_pad($num + 1, 4, "0", STR_PAD_LEFT);
        } else {
            $newID = $prefix . "0001";
        }

        $fullDeptName = $deptMap[$department] ?? $department;

        // 3. Insert into Student Table (including Department)
        $stmt = $conn->prepare("INSERT INTO student (StudentID, Name, Email, Password, Department) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $newID, $name, $email, $password, $fullDeptName);
        $stmt->execute();
    }

    if ($conn->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Registered successfully! Your ID is: " . $newID]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed. Email might already exist."]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "System Error: " . $e->getMessage()]);
}

$conn->close();
?>
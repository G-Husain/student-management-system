<?php
session_start();
include("db.php");

/* Admin Security */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* Check if ID is passed */
if(!isset($_GET['id'])){
    header("Location: student.php");
    exit();
}

$id = intval($_GET['id']);

/* Fetch student data */
$student = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM student WHERE id='$id'")
);

if(!$student){
    die("Student not found.");
}

/* Handle form submission */
if(isset($_POST['update_student'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $class = mysqli_real_escape_string($conn,$_POST['class']);
    $roll_no = mysqli_real_escape_string($conn,$_POST['roll_no']);

    mysqli_query($conn,
        "UPDATE student SET Name='$name', class='$class', roll_no='$roll_no' WHERE id='$id'"
    );

    header("Location: student.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Student</title>
<link rel="stylesheet" href="style.css">
<style>
/* Simple card style for update form */
.form-card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.1);
    width:400px;
    margin:50px auto;
}

.form-card input, .form-card button{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:16px;
}

.form-card button{
    background: rgba(15, 108, 255,0.9);
    color:white;
    border:none;
    cursor:pointer;
}
</style>
</head>
<body>

<div class="form-card">
<h2>Edit Student</h2>

<form method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($student['Name']); ?>" required>

    <label>Class</label>
    <input type="text" name="class" value="<?php echo htmlspecialchars($student['class']); ?>" required>

    <label>Roll No</label>
    <input type="text" name="roll_no" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>

    <button name="update_student">Update Student</button>
</form>

<p><a href="student.php">Back to Students</a></p>
</div>

</body>
</html>

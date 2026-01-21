<?php
session_start();
include("db.php");

/* Login check */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

/* Admin report */
if($role == 'admin'){

    $totalStudents = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) AS total FROM users WHERE Role='student'")
    )['total'];

    $present = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) AS total FROM attendance WHERE status='present'")
    )['total'];

    $absent = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) AS total FROM attendance WHERE status='absent'")
    )['total'];

} 
/* Student report */
else {

    $totalStudents = 1;

    $present = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) AS total 
        FROM attendance 
        WHERE student_id='$user_id' AND status='present'")
    )['total'];

    $absent = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) AS total 
        FROM attendance 
        WHERE student_id='$user_id' AND status='absent'")
    )['total'];
}

/* Attendance % */
$total = $present + $absent;
$percent = $total > 0 ? round(($present / $total) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reports</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="sidebar">
  <h2>MyAcademy</h2>
  <a href="dashboard.php">Dashboard</a>

  <?php if($role == 'admin'){ ?>
    <a href="student.php">Students</a>
    <a href="attendance.php">Attendance</a>
  <?php } ?>

  <a class="active" href="report.php">Reports</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main">

  <div class="topbar">
    <h1>Attendance Report</h1>
  </div>

  <div class="cards">
    <div class="card">
      <h3><?php echo ($role=='admin') ? 'Total Students' : 'Student'; ?></h3>
      <p><?php echo $totalStudents; ?></p>
    </div>

    <div class="card">
      <h3>Present</h3>
      <p><?php echo $present; ?></p>
    </div>

    <div class="card">
      <h3>Absent</h3>
      <p><?php echo $absent; ?></p>
    </div>

    <div class="card">
      <h3>Attendance %</h3>
      <p><?php echo $percent; ?>%</p>
    </div>
  </div>

</div>

</body>
</html>

<?php
session_start();
include("db.php");

// Login check
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<?php
// if($role == 'admin'){

    $totalStudents = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) as total FROM users WHERE Role='student'")
    )['total'];

    $presentToday = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) as total FROM attendance WHERE status='present' AND date=CURDATE()")
    )['total'];

    $absentToday = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) as total FROM attendance WHERE status='absent' AND date=CURDATE()")
    )['total'];

    $total = $presentToday + $absentToday;
    $attendancePercent = $total > 0 ? round(($presentToday/$total)*100) : 0;

// } else {

    // $present = mysqli_fetch_assoc(
    //     mysqli_query($conn,"SELECT COUNT(*) as total FROM attendance WHERE student_id='$user_id' AND status='present'")
    // )['total'];

    // $absent = mysqli_fetch_assoc(
    //     mysqli_query($conn,"SELECT COUNT(*) as total FROM attendance WHERE student_id='$user_id' AND status='absent'")
    // )['total'];

    // $total = $present + $absent;
    // $attendancePercent = $total > 0 ? round(($present/$total)*100) : 0;
// }
?>





<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modern Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
  <h2>MyAcademy</h2>

  <a href="dashboard.php">Dashboard</a>

  <?php if($role == 'admin'){ ?>
      <a href="student.php">Students</a>
      <a href="attendance.php">Attendance</a>
      <a href="report.php">Reports</a>
      <a href="settings.php">Settings</a>
  <?php } ?>

  <!-- <?php if($role == 'student'){ ?>
      <a href="attendance.php">My Attendance</a>
      <a href="report.php">My Report</a>
      <a href="settings.php">Settings</a>
  <?php } ?> -->

  <a href="logout.php">Logout</a>
</div>


<div class="main">

  <!-- Top Header -->
  <div class="topbar">
    <h1>Dashboard</h1>
    <img src="https://via.placeholder.com/40" class="profile">
  </div>

  <!-- Cards Section -->
 <div class="cards">

<!-- <?php if($role == 'admin'){ ?> -->

  <div class="card">
    <h3>Total Students</h3>
    <p><?php echo $totalStudents; ?></p>
  </div>

  <div class="card">
    <h3>Present Today</h3>
    <p><?php echo $presentToday; ?></p>
  </div>

  <div class="card">
    <h3>Absent Today</h3>
    <p><?php echo $absentToday; ?></p>
  </div>
<!-- 
<?php } else { ?>

  <div class="card">
    <h3>Present</h3>
    <p><?php echo $present; ?></p>
  </div>

  <div class="card">
    <h3>Absent</h3>
    <p><?php echo $absent; ?></p>
  </div>

<?php } ?> -->

  <div class="card">
    <h3>Attendance %</h3>
    <p><?php echo $attendancePercent; ?>%</p>
  </div>

</div>


  <!-- Activity -->
  <div class="activity">
    <h2>Recent Activity</h2>
    <ul>
      <li>✔ Ali marked present</li>
      <li>✔ Sara marked absent</li>
      <li>✔ New student added</li>
      <li>✔ Attendance updated</li>
    </ul>
  </div>

</div>

<script src="app.js"></script>
</body>
</html>

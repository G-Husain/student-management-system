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

  $present = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) as total FROM attendance WHERE student_id='$user_id' AND status='present'")
    )['total'];

    $absent = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) as total FROM attendance WHERE student_id='$user_id' AND status='absent'")
    )['total'];

    $total = $present + $absent;
    $attendancePercent = $total > 0 ? round(($present/$total)*100) : 0;

    ?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
  <h2>MyAcademy</h2>

  <a href="dashboard.php" class="active">Dashboard</a>
  <a href="attendance.php">My Attendance</a>
  <a href="report.php">My Report</a>
  <a href="settings.php">Settings</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main">

  <!-- Top Header -->
  <div class="topbar">
    <h1>Student Dashboard</h1>
    <img src="https://via.placeholder.com/40" class="profile">
  </div>

  <!-- Cards Section -->
 <div class="cards">

  <div class="card">
    <h3>Total Days Present</h3>
    <p>18</p>
  </div>

  <div class="card">
    <h3>Total Days Absent</h3>
    <p>2</p>
  </div>

  <div class="card">
    <h3>Attendance %</h3>
    <p>90%</p>
  </div>

  <div class="card">
    <h3>Class</h3>
    <p>9th</p>
  </div>

</div>

  <!-- Recent Attendance -->
  <div class="activity">
    <h2>Recent Attendance</h2>
    <ul>
      <li>✔ 2026-01-15 - Present</li>
      <li>✔ 2026-01-16 - Present</li>
      <li>✖ 2026-01-17 - Absent</li>
      <li>✔ 2026-01-18 - Present</li>
    </ul>
  </div>

</div>

<script src="app.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
  <h2>MyAcademy</h2>
  <a href="dashboard.php">Dashboard</a>
  <a href="student.php">Students</a>
  <a href="attendance.php">Attendance</a>
  <a class="active" href="reports.php">Reports</a>
  <a href="settings.php">Settings</a>
</div>

<div class="main">

  <!-- Topbar -->
  <div class="topbar">
    <h1>Reports</h1>
    <img src="https://via.placeholder.com/40" class="profile">
  </div>

  <!-- Cards Section -->
  <div class="cards">
    <div class="card">
      <h3>Total Students</h3>
      <p>250</p>
    </div>
    <div class="card">
      <h3>Present Today</h3>
      <p>138</p>
    </div>
    <div class="card">
      <h3>Absent Today</h3>
      <p>112</p>
    </div>
    <div class="card">
      <h3>Attendance %</h3>
      <p>88%</p>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="chart-section">
    <h2>Attendance Chart</h2>
    <div class="chart-placeholder">
      Chart will appear here
    </div>
  </div>

</div>

</body>
</html>

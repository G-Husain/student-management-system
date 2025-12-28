<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
  <h2>MyAcademy</h2>
  <a href="dashboard.php">Dashboard</a>
  <a href="students.php">Students</a>
  <a href="attendance.php">Attendance</a>
  <a href="report.php">Reports</a>
  <a class="active" href="settings.php">Settings</a>
</div>

<div class="main">

  <!-- Topbar -->
  <div class="topbar">
    <h1>Settings</h1>
    <img src="https://via.placeholder.com/40" class="profile">
  </div>

  <!-- Profile Settings -->
  <div class="form-card">
    <h2>Profile Settings</h2>
    <input type="text" placeholder="Full Name">
    <input type="email" placeholder="Email">
    <button>Update Profile</button>
  </div>

  <!-- Change Password -->
  <div class="form-card">
    <h2>Change Password</h2>
    <input type="password" placeholder="Current Password">
    <input type="password" placeholder="New Password">
    <input type="password" placeholder="Confirm New Password">
    <button>Change Password</button>
  </div>

</div>

</body>
</html>

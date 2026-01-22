<?php
session_start();
include("db.php");

// Security check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student info
$student = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT Name, class, roll_no FROM student WHERE id='$user_id'")
);

// Fetch all attendance for this student
$attendance_result = mysqli_query($conn,
    "SELECT date, status FROM attendance WHERE student_id='$user_id' ORDER BY date ASC"
);

$present = 0; $absent = 0;
$attendance_records = [];

while($row = mysqli_fetch_assoc($attendance_result)){
    $attendance_records[] = $row;
    if($row['status']=='present') $present++;
    else if($row['status']=='absent') $absent++;
}

$total_days = $present + $absent;
$attendancePercent = $total_days > 0 ? round(($present/$total_days)*100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard</title>
<link rel="stylesheet" href="style.css">
<style>
body{margin:0;font-family:Arial;background:#f4f6f9;}
.sidebar{width:230px;height:100vh;background:#0f6cff;color:white;padding:20px;position:fixed;top:0;left:0;}
.sidebar h2{margin-bottom:30px;}
.sidebar a{display:block;padding:12px;margin-bottom:10px;color:white;text-decoration:none;border-radius:8px;}
.sidebar a:hover,.sidebar a.active{background:white;color:#0f6cff;}
.main{margin-left:250px;padding:20px;}
.topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.cards{display:flex;gap:20px;margin-bottom:20px;flex-wrap:wrap;}
.card{flex:1 1 180px;background:white;padding:20px;border-radius:12px;text-align:center;box-shadow:0 4px 10px rgba(0,0,0,.1);}
.card h3{color:#0f6cff;margin:0 0 10px 0;}
.activity{background:white;padding:20px;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.1);overflow-x:auto;}
table{width:100%;border-collapse:collapse;margin-top:20px;}
th,td{border:1px solid #ddd;padding:8px;text-align:center;}
th{background:#f0f0f0;}
</style>
</head>
<body>

<div class="sidebar">
  <h2>MyAcademy</h2>
  <a href="logout.php">Logout</a>
</div>

<div class="main">

  <div class="topbar">
    <h1>Welcome, <?php echo htmlspecialchars($student['Name']); ?></h1>
    <p>Class: <?php echo htmlspecialchars($student['class']); ?> | Roll No: <?php echo htmlspecialchars($student['roll_no']); ?></p>
  </div>

  <!-- Cards -->

  <!-- Attendance Table -->
  <div class="activity">
    <h2>Attendance Record</h2>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($attendance_records)) {
          foreach($attendance_records as $att){ ?>
          <tr>
            <td><?php echo $att['date']; ?></td>
            <td><?php echo ucfirst($att['status']); ?></td>
          </tr>
        <?php } } else { ?>
          <tr><td colspan="2">No attendance records found.</td></tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

</div>

</body>
</html>

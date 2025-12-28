<?php
session_start();
include("db.php");

/* Admin security */
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

/* Attendance insert */
if(isset($_GET['student_id']) && isset($_GET['status'])){
    $student_id = $_GET['student_id'];
    $status = $_GET['status'];
    $date = date("Y-m-d");

    // duplicate check (same day)
    $check = mysqli_query($conn,
        "SELECT * FROM attendance 
         WHERE student_id='$student_id' AND date='$date'"
    );

    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn,
            "INSERT INTO attendance (student_id,date,status)
             VALUES ('$student_id','$date','$status')"
        );
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Attendance</title>
<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="sidebar">
  <h2>MyAcademy</h2>
  <!-- <a href="dashboard.php">Dashboard</a>
  <a href="student.php">Students</a> -->
  <!-- <a class="active" href="attendance.php">Attendance</a>
  <a href="report.php">Reports</a>
  <a href="settings.php">Settings</a> -->
</div>

<div class="main">

  <!-- Topbar -->
  <div class="topbar">
    <h1>Attendance</h1>
    <img src="https://via.placeholder.com/40" class="profile">
  </div>

  <!-- Attendance Table -->
  <div class="table-section">
    <h2>Mark Attendance</h2>
    <table>
      <thead>
        <tr>
          <th>Roll</th>
          <th>Name</th>
          <th>Class</th>
          <th>Present</th>
          <th>Absent</th>
        </tr>
      </thead>
  <tbody>

<?php
$students = mysqli_query($conn,"SELECT id, Name, Class FROM users WHERE Role='student'");
$roll = 1;

while($s = mysqli_fetch_assoc($students)){
?>
<tr>
  <td><?php echo $roll++; ?></td>
  <td><?php echo $s['Name']; ?></td>
  <td><?php echo $s['Class']; ?></td>

  <td>
    <a href="attendance.php?student_id=<?php echo $s['id']; ?>&status=present">
      <button class="presentBtn">Present</button>
    </a>
  </td>

  <td>
    <a href="attendance.php?student_id=<?php echo $s['id']; ?>&status=absent">
      <button class="absentBtn">Absent</button>
    </a>
  </td>
</tr>
<?php } ?>

</tbody>

    </table>
  </div>

</div>

</body>
</html>

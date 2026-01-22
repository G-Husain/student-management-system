<?php
session_start();
include("db.php");

/* =========================
   ADMIN SECURITY
========================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* =========================
   Attendance Save Logic
========================= */
if(isset($_POST['student_id'], $_POST['mark'])){
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $status = mysqli_real_escape_string($conn, $_POST['mark']);
    $today = date('Y-m-d');

    // Check if attendance already exists
    $check = "SELECT * FROM attendance WHERE student_id='$student_id' AND date='$today'";
    $res = mysqli_query($conn, $check);

    if(mysqli_num_rows($res) > 0){
        // Update existing record
        $sql = "UPDATE attendance SET status='$status' WHERE student_id='$student_id' AND date='$today'";
    } else {
        // Insert new record
        $sql = "INSERT INTO attendance (student_id, date, status) VALUES ('$student_id','$today','$status')";
    }
    mysqli_query($conn, $sql);

    // Redirect to avoid resubmission on refresh
    header("Location: attendance.php");
    exit();
}

/* =========================
   Fetch Students & Attendance
========================= */
$today = date('Y-m-d');
$sql = "SELECT s.id AS student_id, s.Name, s.class, s.roll_no, 
        a.status 
        FROM student s
        LEFT JOIN attendance a 
        ON s.id = a.student_id AND a.date='$today'
        ORDER BY s.roll_no ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance</title>
<link rel="stylesheet" href="style.css">
<style>
/* Button Colors */
button.present { background-color: #28a745; color: white; padding: 5px 12px; border-radius: 4px; border: none; cursor: pointer; }
button.absent { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 4px; border: none; cursor: pointer; }
button.present:hover { background-color: #218838; }
button.absent:hover { background-color: #c82333; }
</style>
</head>

<body>

<div class="sidebar">
  <h2>MyAcademy</h2>
  <a href="dashboard.php">Dashboard</a>
  <a class="active" href="attendance.php">Attendance</a>
  <a href="student.php">Students</a>
  <a href="report.php">Reports</a> 
  <a href="settings.php">Settings</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main">

  <div class="topbar">
    <h1>Attendance</h1>
  </div>

  <div class="table-section">
    <h2>Mark Attendance (<?php echo $today; ?>)</h2>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Class</th>
          <th>Roll_No</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
<?php
$roll = 1;
while($s = mysqli_fetch_assoc($result)){
    $status = $s['status'] ?? '';
?>
<tr>
<td><?php echo $roll++; ?></td>
<td><?php echo $s['Name']; ?></td>
<td><?php echo $s['class']; ?></td>
<td><?php echo $s['roll_no']; ?></td>
<td><?php echo $status; ?></td>
<td>
    <form method="post">
        <input type="hidden" name="student_id" value="<?php echo $s['student_id']; ?>">
        <button type="submit" name="mark" value="Present" class="present">Present</button>
        <button type="submit" name="mark" value="Absent" class="absent">Absent</button>
    </form>
</td>
</tr>
<?php } ?>
      </tbody>
    </table>
  </div>

</div>

</body>
</html>

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
   FETCH STUDENTS
========================= */
$sql = "SELECT id, Name, class, roll_no FROM student ORDER BY class, roll_no";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Student fetch failed: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<!-- ===== SIDEBAR (UNCHANGED) ===== -->
<div class="sidebar">
  <h2>MyAcademy</h2>
  <a href="dashboard.php">Dashboard</a>
  <a class="active" href="students.php">Students</a>
  <a href="attendance.php">Attendance</a>
  <a href="report.php">Reports</a>
  <a href="settings.php">Settings</a>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main">

  <div class="table-section">
    <h2>All Enrolled Students</h2>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Class</th>
          <th>Roll No</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>

<?php
$count = 1;
while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
  <td><?php echo $count++; ?></td>
  <td><?php echo htmlspecialchars($row['Name']); ?></td>
  <td><?php echo htmlspecialchars($row['class']); ?></td>
  <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
  <td>
   <button id="upd_btn"><a href="">Update</a></button><button id="dlt_btn"><a href="">Delete</a></button>
  </td>
</tr>
<?php } ?>

<?php if (mysqli_num_rows($result) == 0) { ?>
<tr>
  <td colspan="5">No students found</td>
</tr>
<?php } ?>

      </tbody>
    </table>
  </div>

</div>

</body>
</html>

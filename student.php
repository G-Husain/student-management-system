<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("db.php");

/* =========================
   ADMIN SECURITY
========================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* =========================
   DELETE STUDENT
========================= */
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // safer

    // Delete attendance first
    $del_attendance = mysqli_query($conn, "DELETE FROM attendance WHERE student_id = $delete_id");
    if (!$del_attendance) {
        die("Error deleting attendance: " . mysqli_error($conn));
    }

    // Delete student
    $del_student = mysqli_query($conn, "DELETE FROM student WHERE id = $delete_id");
    if (!$del_student) {
        die("Error deleting student: " . mysqli_error($conn));
    }

    // Redirect after deletion
    header("Location: student.php");
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

<!-- ===== SIDEBAR ===== -->
<div class="sidebar_std">
  <h2>MyAcademy</h2>
  <a href="dashboard.php">Dashboard</a>
  <a class="active" href="student.php">Students</a>
  <a href="attendance.php">Attendance</a>
  <a href="report.php">Reports</a>
   <a href="logout.php">Logout</a>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main">
   <div class="topbar">
  <h1>Enrolled Students</h1>
<p style="font-size: 18px;">List of all students currently enrolled in the academy</p>
   </div>

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

    <!-- UPDATE -->
    <a href="edit_student.php?id=<?php echo $row['id']; ?>" id="upd_btn">
      Update
    </a>

    &nbsp;

    <!-- DELETE -->
  <a href="student.php?delete_id=<?php echo $row['id']; ?>"
   onclick="return confirm('Are you sure you want to delete this student?');">
    <button id="dlt_btn">Delete</button>
</a>

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

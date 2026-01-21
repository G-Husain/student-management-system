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
   MARK ATTENDANCE (POST)
========================= */
if (isset($_POST['student_id']) && isset($_POST['status'])) {

    $student_id = $_POST['student_id'];
    $status     = $_POST['status'];
    $date       = date("Y-m-d");

    // check if attendance already exists for today
    $check = mysqli_query(
        $conn,
        "SELECT id FROM attendance 
         WHERE student_id='$student_id' AND date='$date'"
    );

    if (!$check) {
        die("Check query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check) == 0) {

        mysqli_query(
            $conn,
            "INSERT INTO attendance (student_id, date, status)
             VALUES ('$student_id', '$date', '$status')"
        );

    } else {

        mysqli_query(
            $conn,
            "UPDATE attendance 
             SET status='$status'
             WHERE student_id='$student_id' AND date='$date'"
        );
    }

    header("Location: attendance.php?success=1");
    exit();
}

/* =========================
   FETCH STUDENTS + TODAY STATUS
========================= */
$today = date("Y-m-d");

$sql = "
SELECT 
    u.id,
    u.name,
    u.class,
    a.status
FROM users u
LEFT JOIN attendance a 
    ON u.id = a.student_id 
    AND a.date = '$today'
WHERE u.role = 'student'
";

$students = mysqli_query($conn, $sql);

if (!$students) {
    die("Student fetch failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance</title>
<link rel="stylesheet" href="style.css">
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

<?php if (isset($_GET['success'])) { ?>
  <p style="color:green; font-weight:bold;">
    Attendance marked successfully!
  </p>
<?php } ?>

  <div class="table-section">
    <h2>Mark Attendance (<?php echo $today; ?>)</h2>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Class</th>
          <th>Status</th>
          <th>Present</th>
          <th>Absent</th>
        </tr>
      </thead>
      <tbody>

<?php
$roll = 1;
while ($s = mysqli_fetch_assoc($students)) {
?>
<tr>
  <td><?php echo $roll++; ?></td>
  <td><?php echo htmlspecialchars($s['name']); ?></td>
  <td><?php echo htmlspecialchars($s['class']); ?></td>

  <td>
    <?php
      if ($s['status'] == 'present') {
          echo "<span style='color:green;font-weight:bold'>Present</span>";
      } elseif ($s['status'] == 'absent') {
          echo "<span style='color:red;font-weight:bold'>Absent</span>";
      } else {
          echo "<span style='color:gray'>Not Marked</span>";
      }
    ?>
  </td>

  <td>
    <form method="POST">
      <input type="hidden" name="student_id" value="<?php echo $s['id']; ?>">
      <input type="hidden" name="status" value="present">
      <button class="presentBtn">Present</button>
    </form>
  </td>

  <td>
    <form method="POST">
      <input type="hidden" name="student_id" value="<?php echo $s['id']; ?>">
      <input type="hidden" name="status" value="absent">
      <button class="absentBtn">Absent</button>
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

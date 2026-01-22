<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("db.php");

/* =========================
   LOGIN CHECK
========================= */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

/* =========================
   DATE & FILTERS
========================= */
$today = date('Y-m-d');
$filterDate  = isset($_GET['date']) ? $_GET['date'] : $today;
$filterClass = isset($_GET['class']) ? $_GET['class'] : "";

/* =========================
   ADMIN LOGIC
========================= */
if($role == 'admin'){

    // class filter
    $classSQL = "";
    if($filterClass != ""){
        $class = mysqli_real_escape_string($conn,$filterClass);
        $classSQL = "AND s.class='$class'";
    }

    // cards
    $totalStudents = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT COUNT(*) total FROM student")
    )['total'];

    $present = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT COUNT(*) total 
            FROM attendance 
            WHERE date='$filterDate' AND status='present'
        ")
    )['total'];

    $absent = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT COUNT(*) total 
            FROM attendance 
            WHERE date='$filterDate' AND status='absent'
        ")
    )['total'];

    // report table
    $report = mysqli_query($conn,"
        SELECT s.roll_no, s.Name, s.class, a.status
        FROM student s
        LEFT JOIN attendance a
          ON s.id = a.student_id
         AND a.date = '$filterDate'
        WHERE 1 $classSQL
        ORDER BY s.roll_no ASC
    ");
}
/* =========================
   STUDENT LOGIC
========================= */
else{

    $student_id = $_SESSION['student_id']; // MUST be student.id

    $totalStudents = 1;

    $present = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT COUNT(*) total 
            FROM attendance 
            WHERE student_id='$student_id' AND status='present'
        ")
    )['total'];

    $absent = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT COUNT(*) total 
            FROM attendance 
            WHERE student_id='$student_id' AND status='absent'
        ")
    )['total'];

    $report = mysqli_query($conn,"
        SELECT date,status 
        FROM attendance 
        WHERE student_id='$student_id'
        ORDER BY date DESC
    ");
}

/* =========================
   PERCENTAGE
========================= */
$total = $present + $absent;
$percent = ($total > 0) ? round(($present/$total)*100) : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance Report</title>

<style>
body{margin:0;font-family:Arial;background:#f4f6f9}

/* Sidebar */
.sidebar{
  width:190px;height:100vh;background:#0f6cff;
  color:white;padding:20px;position:fixed;
}
.sidebar h2{margin-bottom:30px;}
.sidebar a{
  display:block;padding:12px;margin-bottom:10px;
  color:white;text-decoration:none;border-radius:8px;
}
.sidebar a:hover,.sidebar a.active{
  background:white;color:#0f6cff;
}

/* Main */
.main{margin-left: 264px;padding:20px}

/* Cards */
.cards{display:flex;gap:20px;margin-bottom:20px}
.card{
  flex:1;background:white;padding:20px;
  border-radius:12px;text-align:center;
  box-shadow:0 4px 10px rgba(0,0,0,.1)
}
.card h3{color:#0f6cff}

/* Filter */
.filter-form{display:flex;gap:10px;margin-bottom:20px}
.filter-form input,.filter-form select,.filter-form button{
  padding:8px
}

/* Table */
table{ width: 100%; height: 100%; border-collapse:collapse;background:white}
th,td{padding:10px;border-bottom:1px solid #ddd; height:30px;}
th{background:#f0f0f0}
.present{color:green;font-weight:bold}
.absent{color:red;font-weight:bold}
</style>
</head>

<body>

<div class="sidebar">
  <h2>MyAcademy</h2>
  <a href="dashboard.php">Dashboard</a>

  <?php if($role=='admin'){ ?>
    <a href="student.php">Students</a>
    <a href="attendance.php">Attendance</a>
  <?php } ?>

  <a class="active" href="report.php">Reports</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main">

<h1>Attendance Report</h1>

<!-- Cards -->
<div class="cards">
  <div class="card">
    <h3><?php echo ($role=='admin')?'Students':'You'; ?></h3>
    <p><?php echo $totalStudents; ?></p>
  </div>
  <div class="card"><h3>Present</h3><p><?php echo $present; ?></p></div>
  <div class="card"><h3>Absent</h3><p><?php echo $absent; ?></p></div>
  <div class="card"><h3>%</h3><p><?php echo $percent; ?>%</p></div>
</div>

<!-- Filters -->
<?php if($role=='admin'){ ?>
<form method="get" class="filter-form">
  <input type="date" name="date" value="<?php echo $filterDate; ?>">
  <select name="class">
    <option value="">All Classes</option>
    <?php
    $cls = mysqli_query($conn,"SELECT DISTINCT class FROM student");
    while($c=mysqli_fetch_assoc($cls)){
      $sel = ($filterClass==$c['class'])?'selected':'';
      echo "<option $sel>{$c['class']}</option>";
    }
    ?>
  </select>
  <button>Filter</button>
</form>
<?php } ?>

<div id="table_container_report">
<!-- Table -->
<table>
<thead>
<tr>
<?php if($role=='admin'){ ?>
  <th>Roll</th><th>Name</th><th>Class</th><th>Status</th>
<?php } else { ?>
  <th>Date</th><th>Status</th>
<?php } ?>
</tr>
</thead>

<tbody>
<?php while($r=mysqli_fetch_assoc($report)){ ?>
<tr>
<?php if($role=='admin'){ ?>
  <td><?php echo $r['roll_no']; ?></td>
  <td><?php echo $r['Name']; ?></td>
  <td><?php echo $r['class']; ?></td>
  <td>
    <?php
      if($r['status']=='Present') echo "<span class='present'>Present</span>";
      elseif($r['status']=='Absent') echo "<span class='absent'>Absent</span>";
      else echo "Not Marked";
    ?>
  </td>
<?php } else { ?>
  <td><?php echo $r['date']; ?></td>
  <td><?php echo ucfirst($r['status']); ?></td>
<?php } ?>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</body>
</html>

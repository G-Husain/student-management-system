<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("db.php");

// Login check
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// =======================
// CARDS DATA
// =======================
$totalStudents = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM student")
)['total'];

$presentToday = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM attendance WHERE status='present' AND date=CURDATE()")
)['total'];

$absentToday = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM attendance WHERE status='absent' AND date=CURDATE()")
)['total'];

$total = $presentToday + $absentToday;
$attendancePercent = $total > 0 ? round(($presentToday/$total)*100) : 0;

// =======================
// CLASS-WISE DATA FOR BAR CHART
// =======================
$classData = [];
$classes = mysqli_query($conn,"SELECT DISTINCT class FROM student");
while($c=mysqli_fetch_assoc($classes)){
    $className = $c['class'];
    $present = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM student s 
        JOIN attendance a ON s.id=a.student_id 
        WHERE s.class='$className' AND a.status='present' AND a.date=CURDATE()"))['total'];
    $absent = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM student s 
        JOIN attendance a ON s.id=a.student_id 
        WHERE s.class='$className' AND a.status='absent' AND a.date=CURDATE()"))['total'];
    $classData[] = ['class'=>$className,'present'=>$present,'absent'=>$absent];
}

// Prepare JS arrays
$classLabels = json_encode(array_column($classData,'class'));
$presentValues = json_encode(array_column($classData,'present'));
$absentValues  = json_encode(array_column($classData,'absent'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<style>
body{margin:0;font-family: Arial, Helvetica, sans-serif;background:#f4f6f9}
.sidebar_dashboard{width:190px;height:100vh;background:#0f6cff;color:white;padding:20px;position:fixed;}
.sidebar_dashboard h2{margin-bottom:30px;}
.sidebar_dashboard a{display:block;padding:12px;margin-bottom:10px;color:white;text-decoration:none;border-radius:8px;}
.sidebar_dashboard a:hover,.sidebar_dashboard a.active{background:white;color:#0f6cff;}
.main{    margin-left: 260px;padding:20px}
.topbar{display:flex;justify-content:space-between;align-items:center;justify-content: center; margin-bottom:20px;}
.cards{display:flex;gap:20px;margin-bottom:20px;flex-wrap:wrap}
.card{flex:1 1 180px;background:white;padding:20px;border-radius:12px;text-align:center;box-shadow:0 4px 10px rgba(0,0,0,.1);}
.card h3{color:#0f6cff;margin:0 0 10px 0;}
.chart-container{   width:1090px;     height: 85% !important;display:flex;flex-wrap:wrap;gap:20px;}
canvas{background:white;padding:10px;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.1);flex:1 1 300px; max-width:521px; height:300px;}
</style>
</head>
<body>

<div class="sidebar_dashboard">
  <h2>MyAcademy</h2>
  <a class="active" href="dashboard.php">Dashboard</a>
  <a href="student.php">Students</a>
  <a href="attendance.php">Attendance</a>
  <a href="report.php">Reports</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main">
  <div class="topbar">
    <h1>Admin Dashboard</h1>
  </div>

  <!-- Cards -->
  <div class="cards">
    <div class="card">
      <h3>Total Students</h3>
      <p><?php echo $totalStudents; ?></p>
    </div>
    <div class="card">
      <h3>Present Today</h3>
      <p><?php echo $presentToday; ?></p>
    </div>
    <div class="card">
      <h3>Absent Today</h3>
      <p><?php echo $absentToday; ?></p>
    </div>
    <div class="card">
      <h3>Attendance %</h3>
      <p><?php echo $attendancePercent; ?>%</p>
    </div>
  </div>

  <!-- Charts -->
   <div style=" width:100%; height:70vh; display:flex; flex-direction:column;align-items:center; justify-content:center;">
  <h2>Attendance Charts (Today)</h2>
  <div class="chart-container">
    <canvas id="pieChart"></canvas>
    <canvas id="barChart"></canvas>
  </div>
  </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // PIE CHART: Present vs Absent Today
  const ctxPie = document.getElementById('pieChart').getContext('2d');
  const pieChart = new Chart(ctxPie, {
      type: 'pie',
      data: {
          labels: ['Present','Absent'],
          datasets: [{
              data: [<?php echo $presentToday; ?>, <?php echo $absentToday; ?>],
              backgroundColor: ['#28a745','#dc3545']
          }]
      },
      options: {
          responsive:true,
          plugins: { legend: { position:'bottom' } }
      }
  });

  // BAR CHART: Class-wise attendance
  const ctxBar = document.getElementById('barChart').getContext('2d');
  const barChart = new Chart(ctxBar, {
      type: 'bar',
      data: {
          labels: <?php echo $classLabels; ?>,
          datasets: [
            {
              label: 'Present',
              data: <?php echo $presentValues; ?>,
              backgroundColor: '#28a745'
            },
            {
              label: 'Absent',
              data: <?php echo $absentValues; ?>,
              backgroundColor: '#dc3545'
            }
          ]
      },
      options: {
        responsive:true,
        scales: {
          y: { beginAtZero:true, stepSize:1 }
        },
        plugins:{ legend:{ position:'bottom' } }
      }
  });
</script>
</body>
</html>

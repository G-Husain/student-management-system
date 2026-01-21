<?php
session_start();
include("db.php");

/* Login check */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role    = $_SESSION['role'];

/* Fetch user data */
$user = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT Name, Email FROM users WHERE id='$user_id'")
);

/* Update profile */
if(isset($_POST['update_profile'])){
    $name  = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);

    mysqli_query($conn,
        "UPDATE users SET Name='$name', Email='$email' WHERE id='$user_id'"
    );

    $_SESSION['user_name'] = $name;
    header("Location: settings.php");
}

/* Change password */
$msg = "";
if(isset($_POST['change_password'])){
    $current = $_POST['current'];
    $new     = $_POST['new'];
    $confirm = $_POST['confirm'];

    $check = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT Password FROM users WHERE id='$user_id'")
    );

    if($check['Password'] != $current){
        $msg = "Current password is wrong!";
    } elseif($new != $confirm){
        $msg = "New passwords do not match!";
    } else {
        mysqli_query($conn,
            "UPDATE users SET Password='$new' WHERE id='$user_id'"
        );
        $msg = "Password updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Settings</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
<h2>MyAcademy</h2>

<a href="dashboard.php">Dashboard</a>

<?php if($role=='admin'){ ?>
<a href="student.php">Students</a>
<a href="attendance.php">Attendance</a>
<a href="report.php">Reports</a>
<?php } ?>

<a class="active" href="settings.php">Settings</a>
<a href="logout.php">Logout</a>
</div>

<div class="main">
<div class="topbar">
<h1>Settings</h1>
</div>

<div class="form-card">
<h2>Profile Settings</h2>
<form method="post">
<input type="text" name="name" value="<?php echo $user['Name']; ?>" required>
<input type="email" name="email" value="<?php echo $user['Email']; ?>" required>
<button name="update_profile">Update Profile</button>
</form>
</div>

<div class="form-card">
<h2>Change Password</h2>
<form method="post">
<input type="password" name="current" placeholder="Current Password" required>
<input type="password" name="new" placeholder="New Password" required>
<input type="password" name="confirm" placeholder="Confirm New Password" required>
<button name="change_password">Change Password</button>
</form>

<p><?php echo $msg; ?></p>
</div>

</div>
</body>
</html>

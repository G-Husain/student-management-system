<?php
session_start();
include("db.php");
 
$error = "";

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
 
    $query = "SELECT * FROM users WHERE Email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        // simple password check (learning phase)
        if($user['Password'] == $password){  

            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['Name'];
            $_SESSION['role']      = $user['Role'];

            // Role based redirect
            if($user['Role'] == 'admin'){
                header("Location: dashboard.php");
                exit();
            } elseif($user['Role'] == 'student'){
                header("Location: student_dashboard.php");
                exit();
            } else {
                $error = "Role not recognized!";
            }

        } else {
            $error = "Invalid password!";
        }

    } else {
        $error = "Email not found!";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body{
            background-image: url(./images\ \(1\).jfif);
        }
    </style>
</head>
<body>
    

 <div class="form-container">
      <p class="title">Welcome to MyAcademy <p>
<p style="text-align: center;">Please login to continue</p>
      <form class="form"    method="POST" action="">
       <input type="email" name="email" class="input" placeholder="Email" required>
<input type="password" name="password" class="input" placeholder="Password" required>

        <p class="page-link">
          <span class="page-link-label">Forgot Password?</span>
        </p>
        <button class="form-btn" name="login">Log in</button>
      </form>
    </div>


</body>
</html>
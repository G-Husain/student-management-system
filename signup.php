<?php
include("db.php");
$message = "";

if(isset($_POST['signup'])){
    $name = $_POST['FullName'];
    $email = $_POST['email'];
    $password = ($_POST['password']); // simple encryption
    $role=$_POST['role'];


    // Check if email exists
    $check = "SELECT * FROM users WHERE Email='$email'";
    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0){
        $message = "Account already exists!";
    } else {
        $query = "INSERT INTO users (Name,Email,Password,Role) 
                  VALUES ('$name','$email','$password','$role')";
        if(mysqli_query($conn,$query)){
            $message = "Signup successful! You can login now.";
              header("Location: login.php?success=1");
        exit();
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
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

</head>
<body>
    
<form class="form" method="POST" action="">
    <p class="title">Register </p>
    <p class="message">Signup now and get full access to our app. </p>
        <div class="flex">
        <label>
            <input required="" placeholder="" type="text" class="input" name="FullName" require>
            <span>Name</span>
        </label>

        <!-- <label>
            <input required="" placeholder="" type="text" class="input" name="lastname">
            <span>Lastname</span>
        </label> -->
    </div>  
            
    <label>
        <input required="" placeholder="" type="email" class="input" name="email" require>
        <span>Email</span>
    </label> 
        
    <label>
        <input required="" placeholder="" type="password" class="input" name="password" require>
        <span>Password</span>
    </label>
    <label>
        <input required="" placeholder="" type="text" class="input" name="role" require>
        <span>Role i.e student/admin</span>
    </label>
    <button class="submit" name="signup">Submit</button>
    <p class="signin">Already have an acount ? <a href="login.php">Signin</a>
</p>
</form>
</body>
</html>
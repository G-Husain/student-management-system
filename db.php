<?php
$conn = mysqli_connect("localhost","root","","student_database");

if(!$conn){
    die("Database connection failed: ". mysqli_connect_error());
}
?>

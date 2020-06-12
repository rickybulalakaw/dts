<?php
session_start();
require_once("db.php");   

if(isset($_SESSION['id'])) {
            header("Location:index.php");
        } 
        
        if(isset($_SESSION['newemployee'])){
            echo "<h2>Registration Completed</h2>";
            echo "Thank you for registering. You have been registered with the following: <br />Name: ".$_SESSION['unsignedfirstname']." ".$_SESSION['unsignedlastname']."<br /> Email Address: ".$_SESSION['emailid']."<br /> <br />";

            
?>


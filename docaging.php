<?php 

session_start();
require_once("fxns.php");
require_once("db.php");  
 
check_usersignin();

$id = $_SESSION['id'];

$firstname = $_SESSION['firstname'];

display_header();
display_main();

echo "Hello, $firstname.";


?>
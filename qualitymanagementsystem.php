<?php

session_start();
    
if(!isset($_SESSION['id'])) {
    header("Location: signin.php");
    }
    
require_once("db.php");

    

if(isset($_SESSION['qao']) || isset($_SESSION['iac']) || isset($_SESSION['qats']) || isset($_SESSION['iacsec'])) 
    {


            display_header();
    
    echo "<p  align='center'><b>Quality Management System Section</b></p>";
    echo "Hi, ".$_SESSION['firstname'].".<br /><br />";
    
echo "";

display_qms();
    
    
display_home();

} 

ELSE 
    
{
    header("Location: notauthorized.php");
}
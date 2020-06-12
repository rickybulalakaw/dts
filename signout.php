<?php

session_start();
require_once("db.php");
$registersignout = "insert into systemaccess (employeeid, type) VALUES (".$_SESSION['id'].", 'Signout')";
$resultsigout = $db->query($registersignout);
session_destroy();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            
        </title>
    </head>
    <body>
        <meta http-equiv="refresh" content="1;url=signin.php">
    </body>
</html>


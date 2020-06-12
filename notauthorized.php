<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    session_start();
    require_once("db.php");
    
    echo "Sorry, you are not authorized this page. Please click Back on your browser ";
    echo "or <a href='index.php'>view documents you currently have access to</a>";
    

?>
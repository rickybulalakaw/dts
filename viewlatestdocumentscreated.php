<?php

/* This page displays documents created by this office.
 * 
 */
session_start();

if(!isset($_SESSION['id'])) {
            header("Location: signin.php");
        }
        require_once("db.php");

$employeeid = $_SESSION['id'];
$officeid = $_SESSION['officeid'];

$getlatest20docs = "select id, subject, employeeid, datecreated, timestamp from document where originatingunit = '$officeid' and status = 'Active' order by timestamp";
$res20 = $db->query($getlatest20docs);
if($res20 -> num_rows >= 1)
{
    echo "<h1 align='center'>Document Monitoring Matrix</h1>";
    echo "<p align='center'>Below are documents or instructions your office has opened:";
    echo "<table border='1' style='width:100%'>";
    echo "<tr>";
    echo "<td style='background-color:#87CEFA' align='center'>Subject</td>";
    echo "<td style='background-color:#87CEFA' align='center'>Date Created</td>";
    echo "<td style='background-color:#87CEFA' align='center'>Employee ID</td>";
    echo "<td style='background-color:#87CEFA' align='center'>Action</td>";
    echo "</tr>";
    
    while ($row = $res20->fetch_assoc()){
        echo "<tr>";
        echo "<td>".$row['subject']."</td>";
        echo "<td align='center'>".$row['datecreated']."</td>";
        echo "<td align='center'>".$row['employeeid']."</td>";
        echo "<td align='center'><a href='viewrouteddocument.php?id=".$row['id']."'>View Document</a></td>";
        echo "</tr>";
        
    }
    echo "</table><br /><br />";
    echo "<a href='createdocument.php'>Create a new document.</a><br /><br />";
    display_home();
} else {
    echo "Sorry, your office has not registered a document yet.<br /><br />";
    echo "<a href='createdocument.php'>Create a new document.</a><br />";
    display_home();
}



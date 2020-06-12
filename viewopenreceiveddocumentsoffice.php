<?php 

/* 
This page shows documents that are: 
Routed to the office
Still awaiting action by the office

*/

session_start();

require_once("db.php");
require_once("fxns.php");


if(!isset($_SESSION['id'])) {
    header("Location: signin.php");
} else {
    $id = $_SESSION['id'];
}

if(!isset($_GET['officeid'])){
    //header("Location: signin.php");
    echo "Sorry, you are accessing this page with insufficient parameters. Please click <a href='index.php'>here</a> to go to the home page.";
} else {
    $officeid = $_GET['officeid'];
}

display_header();

//echo $officeid;

//start_table();

// Check that the user is assigned to the office 
check_user_office($id, $officeid);

display_main();

// echo "The user has access to the documents routed to this office.";

// Select documents that have trackid that recipient office is $officeid 

$select_opendocument_officerouted = "select distinct DocumentID, DocumentName, TrackDate from openreceiveddocumentsoffice where TrackRecipientOffice = $officeid";
$process = $db->query($select_opendocument_officerouted);
if($process->num_rows == 0){
    echo "Congratulations. There is no open document routed to your office.";
    return;
}

start_table();
cell_blue_hdr_ctr('Document Name');
cell_blue_hdr_ctr('Date Routed');
cell_blue_hdr_ctr('Action');
echo "</tr>";

$i = 1;

while ($row1 = $process->fetch_assoc()){
    echo "<tr>";
    cell_lft($i.". ".$row1['DocumentName']);      
    cell_ctr($row1['TrackDate']);
    cell_ctr_link('View Document Record', "documentactionb.php?did=".$row1['DocumentID']);
    
    echo "</tr>";
    $i++;
  }

  end_table();



?>
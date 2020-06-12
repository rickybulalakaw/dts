<?php 

/* This page displays documents created by a particular office or committee of the user.

It is possible that a user may have multiple offices or committees.

*/

session_start();

require_once("db.php");
require_once("fxns.php");

check_usersignin();

$id = $_SESSION['id'];
$officeid = $_GET['officeid'];

if(!isset($_GET['orderby'])){
	$viewby = 'creationdate';
} else {
	$viewby = $_GET['orderby'];
}

display_header();

display_main();

get_officecreatorname($officeid);

check_user_office($id, $officeid);



$pagelink = "viewopencreateddocumentsoffice.php";
$othervalue = 'officeid='.$officeid;

$getopendocumentsthisoffice = "select id, name, creationdate from document where creatoroffice = $officeid and status = 'Open' order by ".$viewby." asc";
$process1 = $db->query($getopendocumentsthisoffice);
if($process1->num_rows >= 1){

    start_table();
        
        cell_blue_ctr_order('Document ID', "$pagelink", "$othervalue", 'id'); // ($value, $pagelink, $othervalue, $ordervalue)
        cell_blue_ctr_order('Name of Document', "$pagelink", "$othervalue", 'name');
        cell_blue_ctr_order('Date Created', "$pagelink", "$othervalue", 'creationdate');
        //cell_blue_hdr_ctr('Document Initiator');
        cell_blue_hdr_ctr('Action'); 

        echo "</tr>";

        while($row2=$process1->fetch_assoc()) {
        	echo "<tr>";

        	cell_ctr($row2['id']);
        	cell_lft($row2['name']);
        	cell_ctr($row2['creationdate']);
        	cell_ctr("<a href='documentactionb.php?did=".$row2['id']."&officeid=$officeid'>View Document</a>");

        	
        	echo "</tr>";

        }
        
        end_table();

	return;



} else {
	echo "Congratulations! Looks like this office has no open document.";
	return;

}









?>
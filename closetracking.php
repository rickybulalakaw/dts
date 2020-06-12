<?php 

/*
This processing file changes the status of track under the track table to 'Closed'.
*/

session_start();
require_once('db.php');
require_once('fxns.php');

if(!isset($_SESSION['id'])){
    header("Location:signin.php");
}

$id = $_SESSION['id'];

if(!isset($_GET['did'])){
    echo "Sorry, you are accessing this page with insufficient parameters. Please click back on your browser.";
    return;
}

$did = $_GET['did'];

if(!isset($_GET['trackid'])){
    echo "Sorry, you are accessing this page with insufficient parameters. Please click back on your browser.";
    return;
}

$trackid = $_GET['trackid'];



check_document_track_present($did, $trackid);

close_track($id, $did, $trackid);


?>
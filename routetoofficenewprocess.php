<?php 

session_start();

require_once("db.php");
require_once("fxns.php");

$id = $_SESSION['id'];

$employeeid = $id;

check_usersignin();    

    $documentid = strip_tags($_POST['documentid']); // documentid
    if(isset($_POST['trackid'])){
        $trackingid = strip_tags($_POST['trackid']);
        $trackidpresent = 1;
        } else {
        
        $trackidpresent = 0;_
        
    }
    
    $sourceoffice = strip_tags($_POST['sourceoffice']); 
    //$recipientperson = strip_tags($_POST['recipientperson']); 
    $message = strip_tags($_POST['message']);
    $recipienttype = strip_tags($_POST['recipienttype']);
    $creationdate = strip_tags($_POST['creationdate']);
    $onlinedocument = strip_tags($_POST['onlinedocument']);
    

    $sourceoffice = stripslashes($sourceoffice);
    $message = stripslashes($message);
    $creationdate = stripslashes($creationdate);
    $onlinedocument = stripslashes($onlinedocument);   
    
    
    $sourceoffice = mysqli_real_escape_string($db, $sourceoffice);
    $message = mysqli_real_escape_string($db, $message);
    $recipienttype = mysqli_real_escape_string($db, $recipienttype);
    $creationdate = mysqli_real_escape_string($db, $creationdate);
    $onlinedocument = mysqli_real_escape_string($db, $onlinedocument);
  
    foreach($_POST['recipientoffice'] as $receivingoffice){
        require_once("db.php");

        if($sourceoffice == ''){
        echo "Please select a source office.";
        return;
    }

    if($creationdate == ''){
        echo "Please input the appropriate Date of Action.";
        return;
    }
              
                    
        if($trackpresentid == 1) {
        $cascade = "INSERT INTO track (documentid, source, sourceoffice, recipienttype, recipientifoffice, creationdate, message, status, onlinedocument, actionrelatedid) VALUES "
                . "('$documentid', '$employeeid', '$sourceoffice', '$recipienttype', '$receivingoffice', '$creationdate', '$message', 'Sent', '$onlinedocument', '$trackingid')";
                } else {

                    $cascade = "INSERT INTO track (documentid, source, sourceoffice, recipienttype, recipientifoffice, creationdate, message, status, onlinedocument) VALUES "
                . "('$documentid', '$employeeid', '$sourceoffice', '$recipienttype', '$receivingoffice', '$creationdate', '$message', 'Sent', '$onlinedocument')";
        

                }                                   
        if ($check = $db->query($cascade) === TRUE ) {

            $activity = "Route Document $documentid to Office $receivingoffice with message: $message";

            registeractivity($id, $activity);

        } else {
            echo "Error: ".$db->error;
            return;
        }
                    
    } 

    echo "What do you want to do next?<br />";
    echo "<ul>";
    echo "<li><a href= ''>Route this document to offices</a></li>";
    echo "<li><a href= ''>Route this document to specific individuals</a></li>";
    echo "<li><a href='index.php'>Go back to my active documents list.</a></li>";
    echo "</ul>";

    
    // header("Location: viewrouteddocument.php?id=$documentid");
    
?>
<?php 

session_start();

require_once("db.php");
require_once("fxns.php");

$id = $_SESSION['id'];

$employeeid = $id;

check_usersignin();    

    $documentid = strip_tags($_POST['documentid']); // documentid
    $trackingid = strip_tags($_POST['trackid']);
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

    /*if($_POST['recipientoffice'] == ''){
        echo "Please select at least one recipient office. ";
        return;
    }
    */

    
                    // echo $selected."<br />";                     
                    
        $cascade = "INSERT INTO track (documentid, source, sourceoffice, recipienttype, recipientifoffice, creationdate, message, status, onlinedocument, actionrelatedid) VALUES "
                . "('$documentid', '$employeeid', '$sourceoffice', '$recipienttype', '$receivingoffice', '$creationdate', '$message', 'Sent', '$onlinedocument', '$trackingid')";                                   
        if ($check = $db->query($cascade) === TRUE ) {

            $activity = "Route Document $documentid to Office $receivingoffice with message: $message";

            registeractivity($id, $activity);

        } else {
            echo "Error: ".$db->error;
            return;
        }
                    
    } 

    echo "Consider this document acted upon, and remove this document in your active documents list?";
            echo "<ul>";
            echo "<li><a href='closetracking.php?did=$documentid&trackingid=$trackingid'>Yes</a></li>";
            echo "<li><a href='documentactionb.php?did=$documentid'>No, go back to my active documents list.</a></li>";
            echo "</ul>";

    
    // header("Location: viewrouteddocument.php?id=$documentid");
    
?>
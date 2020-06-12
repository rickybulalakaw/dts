<?php

// This page is for DTS2

session_start();
require_once("fxns.php");
require_once("db.php");  
 
check_usersignin();

$id = $_SESSION['id'];

$firstname = $_SESSION['firstname'];
?>
<!DOCTYPE html>

<html>
<head>
<title>Document Tracking System v. 2.0 </title>
</head>

<body>

  <?php 


display_header();

echo "<h1 align='center'>Document Tracking System v.2 </h1>";
display_main();

$checkrmsstaffactive = "select office.acronym from employeeoffice, office where office.id = employeeoffice.officeid and employeeoffice.employeeid = $id and employeeoffice.status = 'Active' and office.acronym = 'RMS'";
$processcheckrms = $db->query($checkrmsstaffactive);

if($processcheckrms->num_rows > 0) {
      $_SESSION['rmsstaff'] = 'Yes';
    } 

    if(isset($_SESSION['rmsstaff'])){
      display_rms();
    }
    
echo "<p align='center'><font color='red'><b>Notice: You are accessing "
    . "privileged information. Please ensure you are accessing this system securely. "
    . "You are responsible for activities done with your login.</b> </font></p>";
    
echo "<p align='center'>Please make sure to log out before you leave your computer.<br /></p>";
   

$selectoffice = "select id from employeeoffice where employeeid = '$id' and status = 'Active'";
$resultselectofficeitem = $db->query($selectoffice);

//$dbprocess = $resultselectoffice->fetch_assoc();
if($resultselectofficeitem->num_rows > 0) {

        // This sequence creates a table
        // The first table will show summary of documents accessible to the offices/committees of the user. 
        // The next table will show summary of documents accessible to the individual.  

        echo "<h2 align='center'>Summary of Open Documents Requiring Office Action or Monitoring</h1>";

        echo "<table border='1' style='width:100%'>";
        echo "<tr>";
        
        cell_blue_hdr_ctr("Office or Committee");
        cell_blue_hdr_ctr("My Membership Type");
        cell_blue_hdr_ctr("Open Documents Created by the Office");
        cell_blue_hdr_ctr("Received Documents Requiring Action");
        echo "</tr>";

        
        foreach ($resultselectofficeitem as $row)
        {

            // This sequence gets the name of the office and membership of the user

            $getofficenameandmember = "select office.id as 'OfficeID', office.name as 'OfficeName', office.acronym, membership.membership as 'Membership' from employeeoffice, membership, office where employeeoffice.membership = membership.id and employeeoffice.officeid = office.id and employeeoffice.id = '".$row['id']."' and employeeoffice.status = 'Active'";
            $resultgetofficenameandmember = $db->query($getofficenameandmember);

            foreach($resultgetofficenameandmember as $key => $resultofficename){
               echo "<tr><td> ".$resultofficename['OfficeName']."  </td>";
               echo "<td  align='center'>  ".$resultofficename['Membership']." </td>";
               

               // This sequence will count the documents created by the office and still open

               // PENDING 

               $countopendocumentofficecreatedopen = "select count(id) as 'Count' from document where creatoroffice = ".$resultofficename['OfficeID']." and status = 'Open'" ;
               $processcountopendocumentofficecreated = $db->query($countopendocumentofficecreatedopen);
               $resultprocesscountopendocumentofficecreated = $processcountopendocumentofficecreated->fetch_assoc();
               //$documentofficecreatednumber = $resultprocesscountopendocumentofficecreated['Count'];


               echo "<td align='center'><a href='viewopencreateddocumentsoffice.php?officeid=".$resultofficename['OfficeID']."'>".$resultprocesscountopendocumentofficecreated['Count']." </a></td>"; 

               // This should be limited to Open documents when clicked

               // This sequence will count the documents received by the office and still open

               // PENDING

               $countopendocumentofficereceived = "select count(DocumentID) as 'Count' from openreceiveddocumentsoffice where TrackRecipientOffice = ".$resultofficename['OfficeID']; // opendocumentofficereceivedtrack
               $processcountopendocumentofficereceived = $db->query($countopendocumentofficereceived);
               $resultprocesscountopendocumentofficereceived = $processcountopendocumentofficereceived->fetch_assoc();
               //$documentofficereceivednumber = $resultprocesscountopendocumentofficereceived['Count'];

               echo "<td align='center'><a href='viewopenreceiveddocumentsoffice.php?officeid=".$resultofficename['OfficeID']."'> ".$resultprocesscountopendocumentofficereceived['Count']."</a></td></tr>";
               // This should be limited to Open documents when clicked
            }
        }

        echo "</table>";
         } else {
        echo "Looks like there is no document routed to your office requiring action nor is there any open document created by your office requiring monitoring.<br /><br />";
        echo "<a href=''>Sign out.</a>";
        return;
    }

    // This will list documents created by the user or documents routed to the specific individual

    echo "<h2 align='center'>Open Individual-level Documents</h2>";

    // Check if there is an open document that has a track that is linked to the user and the status is not "Acted Upon"

    $checkindividualdocumentcreated = "select id from document where creator = $id and status = 'Open'";
    $processcheckindividualdocumentcreated = $db->query($checkindividualdocumentcreated);
    if($processcheckindividualdocumentcreated->num_rows == 0){

      // This sequence checks if there is a document which has been sent to the user via the TrackStatus column

      $checkindividualdocumentrouted = "select TrackID from trackdocumentindividual where RecipientPerson = $id and TrackStatus = 'Sent' or 'Read'";
      $processcheckindividualdocumentrouted = $db->query($checkindividualdocumentrouted);
      if($processcheckindividualdocumentrouted->num_rows == 0){
        echo "Congratulations. You do not have individually created or routed documents currently requiring your action";
        return;
      }

    } 

    echo "<a href=''></a><br />";    

    start_table();
    cell_blue_hdr_ctr("Document Name");
    cell_blue_hdr_ctr("Document Creation Date");
    cell_blue_hdr_ctr("Action");
    

    $getindividualcreateddocumentnos10 = "select distinct DocumentID, DocumentName, DocCreationDate from trackdocumentindividual3 where SourcePerson = $id or RecipientPerson = $id and TrackStatus != 'Acted upon' order by DocCreationDate desc";
    $processgetindividualcreateddocumentnos10 = $db->query($getindividualcreateddocumentnos10);
    $i = 1;
      
    while ($row1 = $processgetindividualcreateddocumentnos10->fetch_assoc()){
      echo "<tr>";
      cell_lft($i.". ".$row1['DocumentName']);      
      cell_ctr($row1['DocCreationDate']);
      cell_ctr_link('View Document Record', "documentactionb.php?did=".$row1['DocumentID']);
      
      echo "</tr>";
      $i++;
    }

    end_table();
    echo "<br />";
    echo "<a href=''>View more individual documents.</a>";
    ?>
    </body>
    </html>

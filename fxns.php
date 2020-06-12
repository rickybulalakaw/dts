<?php
/* This file contains controls and functions of the DTS 2
*/

/* FUNCTIONS ARE GROUPED AND ARRANGED AS FOLLOWS:

1. Logic Functions - Functions that implement business rules, such as access to document or track
2. Activity Functions - Functions that does things in the background with impact in record
3. PAGE ELEMENT FUNCTIONS
4. Table Formatting Functions 

SQL Statements

*/

function make_urls_into_links($plain_text) {
    return preg_replace(
        '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@',
        '<a href="$1">$1</a>', $plain_text);
}

function display_success()
{
    echo "Success.<br />";
    display_home();
}


function display_record()
{
    
    echo "<p align='center'><a href='outputrecordsummaryreport.php'>View summary of outputs and records</a> || ";
    echo "<a href='searchoutput.php'>Search records of agency outputs</a> || ";
    echo "<a href='searchdmsrecord.php'>Search in DMS records</a> || ";
    echo "<a href='recordsummarybyoffice.php'>Summary of documents/records by office</a> || ";
    echo "<a href='recordslatest.php'>View latest recorded documents</a> </p>";
    
}




function get_officecreatorname($officeid){

    // This function gets the name of the office from the GET['officeid']

    require("db.php");

    $get_officecreatorname = "select name, acronym, type from office where id = $officeid";
    $processgetoffice = $db->query($get_officecreatorname);
    $row = $processgetoffice->fetch_assoc();
    $dcreatorofficename = $row['name'];
    $dcreatorofficeacronym = $row['acronym'];
    $dcreatorofficetype = $row['type'];

    echo "<h1 align='center'>$dcreatorofficename</h1>";
} 

function get_officename($officeid){

    // This function gets generic name of office

    require("db.php");

    $get_officename = "select name, acronym, type from office where id = $officeid";
    $processgetoffice = $db->query($get_officename);
    $row = $processgetoffice->fetch_assoc();
    $dcreatorofficename = $row['name'];
    $dcreatorofficeacronym = $row['acronym'];
    $dcreatorofficetype = $row['type'];

}

// LOGIC FUNCTIONS

function check_user_office($id, $officeid){

    require("db.php");  

    $check_user_office = "select id from employeeoffice where employeeid = $id and officeid = $officeid and status = 'Active'";
    $process = $db->query($check_user_office);

    $get_officename = "select name, acronym, type from office where id = $officeid";
    $processgetoffice = $db->query($get_officename);
    $row = $processgetoffice->fetch_assoc();
    $dcreatorofficename = $row['name'];
    $dcreatorofficeacronym = $row['acronym'];
    $dcreatorofficetype = $row['type'];

    if($process->num_rows < 1){
        
        //return;
        header("Location: notauthorized.php"); 
    } 

}

function check_document_exists($did){
    // This sequence checks if the Document ID exists. 

    require("db.php");

    $checkdid = "select id from document where id = $did";
    $proccheckdid = $db->query($checkdid);
    if($proccheckdid->num_rows < 1){
        header("Location: documentdoesnotexist.php");
        return;        
        }
}

function check_document_track_present($did, $trackid){
    require("db.php");
    $getrow = "select id from track where documentid = $did and id = $trackid";
    $process = $db->query($getrow); 
    if($process->num_rows < 1){
        header("Location: inconsistentparameter.php");
    }

}

// ACTIVITY FUNCTIONS

function registeractivity($employeeid, $activity){

    require("db.php");

    $registeractivityhistory = "insert into systemrecord (employeeid, Action) values ('$employeeid', '".$activity."')";
    $processhistory = $db->query($registeractivityhistory);

    }

function check_usersignin(){
    if(!isset($_SESSION['id'])) {
    header("Location: signin.php");
    }
}


function check_user_documentaccess($did, $id){

    require("db.php");

    // This sequence checks that the user is allowed to access this document. 
        // First test is if the user is the creator. 

        $checkdidcreator = "select id from document where id = $did and creator = $id";
        $proccheckcreator = $db->query($checkdidcreator);
        if($proccheckcreator->num_rows < 1){
            //echo $id."<br />";
            //echo $did."<br />";
            
            echo "You are not creator of this document.<br />";

            //Second test is if the user is within the office where the document was created. 

            $checkofficecreator = "select employeeoffice.id from employeeoffice, document where document.id = $did and document.creatoroffice = employeeoffice.officeid and employeeoffice.employeeid = $id";

            $proccheckofficecreator = $db->query($checkofficecreator);

            if($proccheckofficecreator->num_rows < 1){

                echo "You do not belong to the office that created this document.<br />";
                $canclosedocument = 'No';
                //return;
                // Third test is if the user is within the office where the document was routed to.              

                $selectuseroffice = "select officeid from employeeoffice where employeeid = $id and status = 'Active'";
                $proc223 = $db->query($selectuseroffice);
                while ($row4 = $proc223->fetch_assoc())
                {
                    // check if the office is recipient 

                    $checkifofficeisrecipient = "select trackid from documenttrackrecipientofficeemployee where employeeid = $id and documentid = $did";
                    $proc224 = $db->query($checkifofficeisrecipient);
                    if($proc224->num_rows < 1)
                    
                    {

                        echo "None of your offices or committees is not a recipient of this document.<br />";

                        // Final check: Check if the document is routed to the individual user

                        $checkindividualrecipient = "select TrackID from trackdocumentindividual where DocumentID = $did and RecipientPerson = $id";
                    $proccheckindividualrecipient = $db->query($checkindividualrecipient);
                    if($proccheckindividualrecipient->num_rows < 1){
                        header("Location: notauthorized.php");

                        return;
                    }

                    echo "Good for office recipient access<br />";

                    }

                } 

                 //echo "Good for user within office of office creator<br />";
            }

        }

}

function check_trackid($trackid, $id){
    require("db.php");

    // This function checks if the trackid should be accessible to the user. 

    $check_tracker_user = "select * from documenttrackrecipientofficeemployee where trackid = $trackid and employeeid = $id";
    $process = $db->query($check_tracker_user);
    if($process->num_rows < 1){

        header("Location: notauthorized.php");

    }
}

function check_did_and_trackid($did, $trackid){

    require("db.php");

    // This function checks the URL to prevent associating track to a document that is not connected to it

    $check_did_trackid = "select id, documentid from track where id = $trackid and documentid = $did";
    $process = $db->query($check_did_trackid);
    if($process->num_rows == 0){

        //$row = $process->fetch_assoc();
        //$dtid = $row['id'];
        //$ddid = $row['documentid'];

        
        header("Location: inconsistentparameter.php");
        // ?did=$ddid&trackid=$dtid
    }

}

function close_track($id, $did, $trackid){

    require("db.php");
    $close_trackid = "update track SET status = 'Acted upon' where id = $trackid";
    if($db->query($close_trackid) === TRUE){

        registeractivity($id, "Closed Tracking ID no. $trackid");
        header("Location:documentactionb.php?did=$did&trackid=$trackid");

    } else {

        echo "Error: ".$db->error;

    }
}

// PAGE ELEMENT FUNCTIONS

function display_header(){
    echo "<p align='center'><a href='index.php'><img src='images/pms-logo.jpg' alt='pms logo' height='130' width='175' ></a><br />"
        . "<b>Office of the President of the Philippines<br /> "
            . "PRESIDENTIAL MANAGEMENT STAFF</b></p>";
}

function display_home()
{
    echo "<br />Go <a href='index.php'>home</a>.<br />";
}

function check_documentsecurityclass($did){

        require("db.php");

        $getsecurityclass = "select securityclass from document where id = $did";
        $procgetsecurityclass = $db->query($getsecurityclass);
        $row1 = $procgetsecurityclass->fetch_assoc();
        $dbsecurityclass = $row1['securityclass'];

        if($dbsecurityclass <> 'Unrestricted'){
            echo "<p align='center'><b>This document is classified as $dbsecurityclass. Exercise necessary diligence in routing this document whether in paper or through online.</b></p>";
        }
    }



function display_main(){
    echo "<p align='center'>";
    echo "<a href='index.php'>Home</a> || ";
    echo "<a href='createdocument.php'>Create a Document</a> || ";
    echo "<a href='docaging.php'>View Document by Aging</a> || "; // This shows summary of open documents with aging
    echo "<a href='search.php'>Search for a Document</a> || "; 
    echo "<a href='signout.php'>Sign out</a> ";
    echo "</p>";
}

function display_rms(){
    echo "<p align='center'>";
    echo "<a href='createoutputtype.php'>Create a document type</a> || "; 
    echo "<a href='documentdashboard.php'>View All Document Dashboard</a> || "; 
    echo "<a href='removerole.php'>Remove role</a> || "; 
    echo "<a href='removerole.php'>Remove role</a> "; 
    echo "</p>";
}

function display_useractiveoffice_drop($id){ 

require("db.php");   

    echo "<b>Source Office</b>: ";
    echo "<br />";

    echo "<select name='sourceoffice'>";        
    echo "<option value=''></option>";

    $selectactiveoffice = "select office.id, office.name from office, employeeoffice where employeeoffice.officeid = office.id and employeeoffice.employeeid = $id and employeeoffice.status = 'Active' order by office.name asc"; 
    $processselectactiveoffice = $db->query($selectactiveoffice);
    while ($dbofficeactive = $processselectactiveoffice->fetch_assoc()){
           
            echo "<option value=".$dbofficeactive['id'].">".$dbofficeactive['name']."</option>";
        }

    echo "</select>";

}

function document_details_table($did){

    require("db.php");
           
        $getdocumentdetails = "select name, securityclass, timeline, creator, creatoroffice, status, contenttype, onlinelink, creationdate, status, signatory from document where id = $did";
        $processgetdocumentdetails = $db->query($getdocumentdetails);
        $row4 = $processgetdocumentdetails->fetch_assoc();
        $dname = $row4['name'];
        $dsecurityclass = $row4['securityclass'];
        $dtimeline = $row4['timeline'];
        $dcreator = $row4['creator'];
        $dcreatoroffice = $row4['creatoroffice'];
        $dstatus = $row4['status'];
        $dcontenttype = $row4['contenttype'];
        $donlinelink = $row4['onlinelink'];
        $dcreationdate = $row4['creationdate'];
        $dstatus = $row4['status'];
        $dsignatory = $row4['signatory'];        

        $get_officecreatorname = "select name, acronym from office where id = $dcreatoroffice";
        $processgetoffice = $db->query($get_officecreatorname);
        $row = $processgetoffice->fetch_assoc();
        $dcreatorofficename = $row['name'];
        $dcreatorofficeacronym = $row['acronym'];

        $get_individualcreatorname = "select firstname, lastname from employee where id = $dcreator";
        $processgetindividualcreatorname = $db->query($get_individualcreatorname);
        $row6 = $processgetindividualcreatorname->fetch_assoc();
        $icfname = $row6['firstname'];
        $iclname = $row6['lastname'];

        echo "<h2 align='center'>Document Details</h2>";

        start_table();
        cell_blue_lft('Document Name and Control No.');
        echo "<td>$dname || $did</td>";
        cell_blue_lft('Security Class');

        if($dsecurityclass == 'Top Secret'){
            echo "<td style='background-color:#000000' align='center'><b><font color='white'>$dsecurityclass</font></b></td>"; // Black Background

        } else if ($dsecurityclass == 'Secret') {
            echo "<td style='background-color:#ff0000' align='center'><b><font color='white'>$dsecurityclass</font></b></td>"; // Red Background

        } else if ($dsecurityclass == 'Confidential') {
            echo "<td style='background-color:#0000FF' align='center'><b><font color='white'>$dsecurityclass</font></b></td>"; // 

        } else if ($dsecurityclass == 'Restricted'){
            echo "<td style='background-color:#87CEFA' align='center'><b>$dsecurityclass</b></td>";

        } else {
            echo "<td align='center'><b>$dsecurityclass</b></td>";
        }
        echo "</tr>"; 
        
        cell_blue_lft('Creator Office');
        cell_lft($dcreatorofficename);
        cell_blue_lft('Document Timeline');
        cell_ctr($dtimeline);

        echo "<tr>";
        cell_blue_lft('Signatory');
        cell_lft($dsignatory);
        cell_blue_lft('Online Link');
        if($donlinelink == null){
            $donlinelink = "None";
        } else {
            $donlinelink = "<a target='_blank' href='$donlinelink'>View File</a>";
        }
        cell_ctr($donlinelink);        
        echo "</tr>";

        echo "<tr>";
        cell_blue_lft('Document Author');
        echo "<td>$icfname $iclname</td>";
        cell_blue_lft('Creation Date and Status');
        cell_ctr("$dcreationdate / $dstatus");
        echo "</tr>";

        end_table();

        $dsecurityclass = $dsecurityclass;
    }



// TABLE FORMATTING FUNCTIONS

function start_table(){
    echo "<table>";
    echo "<table border='1' style='width:100%'>";
    echo "<tr>";
}

function end_table(){
    echo "</table>";
}

function cell_blue_lft($value){
    echo "<td style='background-color:#87CEFA'>$value</td>";

}

function cell_blue_hdr_ctr($value){
    echo "<td style='background-color:#87CEFA' align='center'><b>$value</b></td>";

}

function cell_blue_hdr_span($value, $span){
    echo "<td style='background-color:#87CEFA' align='center' colspan='$span'><b>$value</b></td>";

}

function cell_ctr($value){
    echo "<td align='center'>$value</td>";

}

function cell_ctr_link($value, $link){
    echo "<td align='center'><a href='$link'>$value</a></td>";

}

function cell_ctr_span($value, $span){
    echo "<td align='center' colspan='$span'>$value</td>";

}

function cell_lft($value){
    echo "<td>$value</td>";

}

function cell_blue_ctr($value){
    echo "<td style='background-color:#87CEFA' align='center'>$value</td>";

}

function cell_blue_ctr_order($value, $pagelink, $othervalue, $ordervalue){
    echo "<td style='background-color:#87CEFA' align='center'><b><a href='$pagelink?$othervalue&orderby=$ordervalue'>$value</b></a></td>";
}

function header1($value){
    echo "<h1 align='center'>$value</h1>";
}

function header2($value){
    echo "<h2 align='center'>$value</h2>";
}

function boldtext($value){
    echo "<b>$value</b>";
}

function italtext($value){
    echo "<i>$value</i>";
}

function bolditaltext($value){
    echo "<b><i>$value</i></b>";
}


// TABLE SQL STATEMENTS

/*

create view documentindividualtrack as select track.documentid as "DocumentID", 
track.id as "TrackID", 
track.recipientifperson as "RecipientPerson",
track.status as "TrackStatus"

from document, track 
where track.documentid = document.id 
and document.status = 'Open'
and track.status <> 'Acted Upon'

order by DocumentID ASC, TrackID ASC

*/

/*

create view trackdocumentindividual3 as
select track.documentid as "DocumentID", 
document.name as "DocumentName",
track.id as "TrackID", 
document.creationdate as "DocCreationDate",
track.source as "SourcePerson",
track.recipientifperson as "RecipientPerson",
track.status as "TrackStatus"

from document, track 
where track.documentid = document.id 
and document.status = 'Open'
and track.status <> 'Acted On'

order by DocumentID ASC, TrackID ASC
*/


/*
create view trackdocumentoffice as
select track.id as "TrackID",
document.id as "DocumentID", 
document.creatoroffice as "DocumentCreatorOffice", 
track.creationdate as "trackdate"
track.sourceoffice as "TrackSourceOffice",
track.recipientifoffice as "TrackRecipientOffice"

from track, document

where document.id = track.documentid and

track.status = "Sent" OR "Read"

*/

?>
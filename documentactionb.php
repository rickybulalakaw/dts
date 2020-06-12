<?php 

/* 

1. This page opens information about a document and shows records of transactions on the document. 
2. It also allows the user to act on a document. 
*/

session_start();

    if(!isset($_SESSION['id'])) {
            header("Location: signin.php");
        }
        
        require_once("db.php");
        require_once("fxns.php");

        

        if(!isset($_GET['did'])){
        	echo "Sorry, you are route this page with insufficient parameters. Please click back on your browser.";
        	return;
        }
      

        $did = $_GET['did'];
        $id = $_SESSION['id'];

        check_document_exists($did);

        check_user_documentaccess($did, $id);

        display_header();
        
        display_main();
        
        header1('Document Routing Slip');
        echo "<p align='center'><a href='printready.php?did=$did'>Print Document Slip</a></p>";

        document_details_table($did);

        
        header2('Document Routing Record');

        $checkroutingrecord = "select id from track where documentid = $did";
        $processcheckroutingrecord = $db->query($checkroutingrecord);
        if($processcheckroutingrecord->num_rows == 0)
        {
            echo "<p align='center'>This document has not been routed yet. <a href='routetoofficenew.php?did=$did'>Add a routing record.</a></p>";
            return;
        }

        //echo "<p align='center'>";        
        //echo "<a href='routetooffice.php?did=$did'>Route to Office/s</a> || "; 
        //echo "<a href='routetoindividual.php?did=$did'>Route to Individual/s</a> ";
        //echo "</p>";

        

        start_table();
        
        cell_blue_hdr_ctr('Document Movement ID');
        cell_blue_hdr_ctr('Date');
        cell_blue_hdr_ctr('From');
        cell_blue_hdr_ctr('To');
        cell_blue_hdr_ctr('Instructions');
        cell_blue_hdr_span('Action', 3);       

        echo "</tr>";

        //$gettrackrecords = "select TrackID, trackdate, TrackSourceOffice, TrackRecipientOffice, TrackRecipientPerson from documenttrackoffice2 where DocumentID = $did";

        $gettrackrecords = "select id, creationdate, source, sourceoffice, recipientifperson, recipientifoffice, message from track where documentid = $did order by timestamp desc";
        $procgettrackrecords = $db->query($gettrackrecords);
        foreach($procgettrackrecords as $key => $dbtrackrecord){

        	// This sets First column Date 

        	echo "<tr>";
        
            cell_ctr($dbtrackrecord['id']);
        	cell_ctr($dbtrackrecord['creationdate']);
        	

        	// This sets Second  column FROM

        	// This sequence gets name of the individual source of the track 

        	$getsourcename = "select firstname, lastname from employee where id = ".$dbtrackrecord['source'];
            $processgetsourcename = $db->query($getsourcename);

            $rowproc = $processgetsourcename->fetch_assoc();
           
            $trfname = $rowproc['firstname'];
            $trlname = $rowproc['lastname'];

            // This sequence gets the acronym of the office source of the track

            $getsourceoffice = "select acronym from office where id = ".$dbtrackrecord['sourceoffice'];
            $processgetsourceoffice = $db->query($getsourceoffice);
            $row7 = $processgetsourceoffice->fetch_assoc();
            

            $trsoffice = $row7['acronym'];

            cell_ctr($trfname." ".$trlname." / ".$trsoffice);

            // This sets Third column TO

            // Check if there is a recipientifperson value 

            // This sequence gets name of the individual source of the track 

            if($dbtrackrecord['recipientifperson'] != null){
                $gettoname = "select firstname, lastname from employee where id = ".$dbtrackrecord['recipientifperson'];
                $processgettoname = $db->query($gettoname);        

                $rowproc2 = $processgettoname->fetch_assoc();
               
                $tofname = $rowproc2['firstname'];
                $tolname = $rowproc2['lastname'];

                // This sequence gets the acronym of the office destination of the track

                $gettooffice = "select acronym from office where id = ".$dbtrackrecord['recipientifoffice'];
                $processgettooffice = $db->query($gettooffice);
                $row8 = $processgettooffice->fetch_assoc();            

                $tooffice = $row8['acronym'];

                 if($dbtrackrecord['recipientifperson'] == $id){
                    cell_blue_ctr($tofname." ".$tolname." / ".$tooffice);

                    
                } else {
                    cell_ctr($tofname." ".$tolname." / ".$tooffice);
                }

            
                

            } else {

                 // This sequence gets the acronym of the office destination of the track

                $gettooffice = "select acronym from office where id = ".$dbtrackrecord['recipientifoffice'];
                $processgettooffice = $db->query($gettooffice);
                $row8 = $processgettooffice->fetch_assoc();
       
                $tooffice = $row8['acronym'];
                cell_ctr($tooffice);
       
            }
           
            //echo "<td>".$dbtrackrecord['message']."</td>";

            cell_lft($dbtrackrecord['message']);

            // TASK: Detect if the track identifies the user or the user's office, and if so, have link for route to office, route to individual, or close the track.

            $checkrecipientofficeifuserismember = "select id from employeeoffice where officeid = ".$dbtrackrecord['recipientifoffice']." and employeeid = $id and status = 'Active'";
            $process22 = $db->query($checkrecipientofficeifuserismember);
            if($process22->num_rows >= 1){

                // This will check if the track is active 

                $checktrackstatus = "select status from track where id = ".$dbtrackrecord['id'];
                $proc22 = $db->query($checktrackstatus);
                $row22 = $proc22->fetch_assoc();
                $dbtrackidstatus = $row22['status'];
                if($dbtrackidstatus != 'Acted upon'){
                    cell_ctr_link("Route to Office", "routetoofficenew.php?did=$did&trackid=".$dbtrackrecord['id']);
                    cell_ctr_link("Route to Individual", "routetoindividualnew.php?did=$did&trackid=".$dbtrackrecord['id']);
                    cell_ctr_link("Close", "closetracking.php?did=$did&trackid=".$dbtrackrecord['id']);
                } else {
                cell_ctr_span('No Action Required', 3);
            }
                
            } else {
                cell_ctr_span('No Action Required', 3);

            }

            
            echo "</tr>";
            
           }

        end_table();
        

?>
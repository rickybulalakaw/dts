<?php

/* This page displays the PCRF of the user. It is used to:
 * 1. Record outputs, based on commitments in PC, for rating by the supervisor.
 * 2. Establish PCRFID, which is used in other tasks that requires PCRFID (by creating a value for $_SESSION['pcrfid'])
 * 
 * Before showing the PCRF data, the page checks if the user is logged in (using session id) and if the user already has PCRF (through session pcrfid). 
 * 
 * This has links for: 
 * 1. Recording outputs
 * 2. Revising individual commitments
 */
session_start();
    
    if(!isset($_SESSION['id'])) {
            header("Location: signin.php");
        }
                
        if($_SESSION['rank'] >= 3) // $_SESSION['rank'] < 3
        {
            echo "This page is only for 1st and 2nd level personnel.";
            echo "Please click <a href='viewpcrf3.php'>here to record outputs based on your PC if you are a third level official</a>.<br />";
            return;
        }   
        
        require_once("db.php");
        
        $sql_fetch_announcement = "select id from announcement order by id DESC";
    
$result = $db->query($sql_fetch_announcement);
    
foreach ($result as $row) { 
        //echo $row['id']."<br />";
$sql_check_read = "select distinct announcementid, employeeid from announcementread where announcementid = ".$row['id']." and employeeid = ".$_SESSION['id']."";
$resultx = $db->query($sql_check_read);
        if ($resultx->num_rows < 1) {  
                
                 header("Location: readannouncement.php?id=".$row['id']."");
            
            } 
            
        }
        
        // Create printable view - change Action to Remarks
        // Create PC for editing commitments - Action to Edit Commitment
        // Create PCRF - Action to Self-Rate
        
if(!isset($_SESSION['pcrfid'])) {
    display_header();        
    echo "Hello, ".$_SESSION['firstname'].". You have not entered any commitments yet.<br /><br />";
    echo "Go to the <a href='ootable.php'>Office Objectives table</a> to add a commitment.<br /><br />";
    } 
    
    require_once("db.php");  
    display_header();
    
    $getpcrfstatus = "select Status from pcrf where PCRFID = '".$_SESSION['pcrfid']."'";
    $resultpcrfstatus = $db->query($getpcrfstatus);
    $rowpcrfstatus = mysqli_fetch_array($resultpcrfstatus);
    $pcrfstatus = $rowpcrfstatus['Status'];
    
    
        
$sql = "SELECT Commitmentid FROM commitment where PCRFID = '".$_SESSION['pcrfid']."'";
$result = $db->query($sql);
        
if($result->num_rows > 0) {

// This sequence collects data on user's first level supervisor and creates session data 

    $sql_fetch_supervisor1 = "SELECT  pcrf.Supervisor1, employee.Firstname, employee.Lastname from employee, pcrf where pcrf.Supervisor1 = employee.Employeeid and pcrf.PCRFID = '".$_SESSION['pcrfid']."'";
    $result2 = $db->query($sql_fetch_supervisor1);
    
    if($result2->num_rows > 0) {
        $query = $db->query($sql_fetch_supervisor1);
        $row = mysqli_fetch_array($query);
        $usersupervisor1 = $row['Supervisor1'];
        $usersupervisor1first = $row['Firstname'];
        $usersupervisor1last = $row['Lastname'];
        
        $_SESSION['usersupervisor1'] = $usersupervisor1;
        $_SESSION['usersupervisor1first'] = $usersupervisor1first;
        $_SESSION['usersupervisor1last'] = $usersupervisor1last;
        
// Get position from PCRF for table of signatories
        
    } ELSE {
        $_SESSION['usersupervisor1'] = "";
        $_SESSION['usersupervisor1first'] = "";
        $_SESSION['usersupervisor1last'] = "";
        }
            
            // This sequence gets data on 1st level supervisor's position from PCRF by getting position of that person's latest PCRF
            
            
        $sql_fetch_supervisor1post = "select Position from pcrf where Employee_ID = '".$_SESSION['usersupervisor1']."'";
        $result5 = $db->query($sql_fetch_supervisor1post);
        
        if($result5->num_rows > 0) {
            $query = mysqli_query($db, $sql_fetch_supervisor1post);
            $row = mysqli_fetch_array($query);
            $supervisor1post = $row['Position'];
            
            $_SESSION['supervisor1post'] = $supervisor1post;
            } ELSE {
                $_SESSION['supervisor1post'] = 'Not applicable';
            }
            
            // This sequence collects data on user's 2nd level supervisor and creates session data 
            
            $sql_fetch_supervisor2 = "SELECT  pcrf.Supervisor2, employee.Firstname, employee.Lastname from employee, pcrf where pcrf.Supervisor2 = employee.Employeeid and pcrf.PCRFID = '".$_SESSION['pcrfid']."'";
            $result3 = $db->query($sql_fetch_supervisor2);
            
            if($result3->num_rows > 0) {
                $query = mysqli_query($db, $sql_fetch_supervisor2);
                $row = mysqli_fetch_array($query);
                $usersupervisor2 = $row['Supervisor2'];
                $usersupervisor2first = $row['Firstname'];
                $usersupervisor2last = $row['Lastname'];
                
                $_SESSION['usersupervisor2'] = $usersupervisor2;
                $_SESSION['usersupervisor2first'] = $usersupervisor2first;
                $_SESSION['usersupervisor2last'] = $usersupervisor2last;
                
                // Get position from PCRF for table of signatories
                
            } ELSE {
                $_SESSION['usersupervisor2'] = "";
                $_SESSION['usersupervisor2first'] = "";
                $_SESSION['usersupervisor2last'] = "";
            }
            
            // This sequence gets data on 2nd level supervisor's position from PCRF by getting position of that person's latest PCRF
            
            $sql_fetch_supervisor2post = "select Position from pcrf where Employee_ID = '".$_SESSION['usersupervisor2']."'";
            $result6 = $db->query($sql_fetch_supervisor1);
            
            if($result6->num_rows > 0) {
                $query = mysqli_query($db, $sql_fetch_supervisor2post);
                $row = mysqli_fetch_array($query);
                $supervisor2post = $row['Position'];
                
                $_SESSION['supervisor2post'] = $supervisor2post;
                
            } ELSE {
                $_SESSION['supervisor2post'] = 'Not applicable';
            }
            
            // This sequence collects data on user's 3rd level supervisor and creates session data 
            
            $sql_fetch_supervisor3 = "SELECT  pcrf.Supervisor3, employee.Firstname, employee.Lastname from employee, pcrf "
                    . "where pcrf.Supervisor3 = employee.Employeeid and pcrf.PCRFID = '".$_SESSION['pcrfid']."'";
            $result4 = $db->query($sql_fetch_supervisor3);
            
            if($result4->num_rows > 0) {
                $query = mysqli_query($db, $sql_fetch_supervisor3);
                $row = mysqli_fetch_array($query);
                $usersupervisor3 = $row['Supervisor3'];
                $usersupervisor3first = $row['Firstname'];
                $usersupervisor3last = $row['Lastname'];
                
                $_SESSION['usersupervisor3'] = $usersupervisor3;
                $_SESSION['usersupervisor3first'] = $usersupervisor3first;
                $_SESSION['usersupervisor3last'] = $usersupervisor3last;
                
                // Get position from PCRF for table of signatories
                
            } ELSE {
                $_SESSION['usersupervisor3first'] = "Not";
                $_SESSION['usersupervisor3last'] = "Applicable";
            }
            
            // This sequence gets data on 2nd level supervisor's position from PCRF by getting position of that person's latest PCRF
            
            $sql_fetch_supervisor3post = "select Position from pcrf where Employee_ID = '".$_SESSION['usersupervisor3']."'";
            $result7 = $db->query($sql_fetch_supervisor3post);
            
            if($result7->num_rows > 0) {
                $query = mysqli_query($db, $sql_fetch_supervisor3post);
                $row = mysqli_fetch_array($query);
                $supervisor3post = $row['Position'];
                
                $_SESSION['supervisor3post'] = $supervisor3post;
                
            } ELSE {
                $_SESSION['supervisor3post'] = 'Not applicable';
            }
            
            // This sequence displays the PCRF page 
            
            echo "<HTML>";
            echo "<HEAD>";
            echo "<TITLE>PMS - Individual PCRF - ".$_SESSION['firstname']." ".$_SESSION['lastname']." ".$_SESSION['extension']."</TITLE>";
            echo "</HEAD>";
            echo "<BODY>";
            echo "";
            echo "";
            
            
            echo "<p align='center'><b>ROPMS Form 1</b><br />";
            echo "<font color='red'>Performance Contract Status: ".$pcrfstatus."</font></p>";
            
            if($_SESSION['position'] == 'Assistant Secretary for Management Support' || 
                    $_SESSION['position'] == 'Assistant Secretary for Management Information Support' || 
                    $_SESSION['position'] == 'Assistant Secretary for Administrative Support' || 
                    $_SESSION['position'] == 'Assistant Secretary for Policy' || 
                    $_SESSION['position'] == 'Assistant Secretary for Executive Support' || 
                    $_SESSION['position'] == 'Assistant Secretary for Monitoring of Public Concerns' || 
                    $_SESSION['position'] == 'Undersecretary'){
                echo "I, <b>".$_SESSION['firstname']." ".$_SESSION['lastname']."</b>, <b>".$_SESSION['extension']."</b> <b>".$_SESSION['position']."</b>, "
                    . "commit to deliver and agree to be rated on the attainment "
                    . "of the following targets in accordance with the indicated measures "
                    . "for the period <b>".$_SESSION['startdate']."</b> to"
                    . " <b>".$_SESSION['enddate']."</b>.<br /><br />"; 
            
            } ELSE {
            
            echo "I, <b>".$_SESSION['firstname']." ".$_SESSION['lastname']."</b>, <b>".$_SESSION['extension']."</b> <b>".$_SESSION['position']."</b> of "
                    . "<b>".$_SESSION['officeid']."</b>, commit to deliver and agree to be rated on the attainment "
                    . "of the following targets in accordance with the indicated measures "
                    . "for the period <b>".$_SESSION['startdate']."</b> to"
                    . " <b>".$_SESSION['enddate']."</b>.<br /><br />"; 
            
            }
            echo "_____________________________________<br />";
            echo "<br />";
            
            // Folowing sequence creates table for PC signatories
            echo "<table border='1' style='width:100%'>";
                echo "<tr><td style='background-color:#87CEFA' align='center'>Reviewed by</td><td style='background-color:#87CEFA' align='center'>Endorsed by</td><td style='background-color:#87CEFA' align='center'>Approved by</td></tr>";
           
            echo "<tr><td rowspan='2' align='center'><br /><b>".$_SESSION['usersupervisor1first']." ".$_SESSION['usersupervisor1last']."</b><br />".$_SESSION['supervisor1post']."</td>"
                    . "<td align='center'><br /><b>".$_SESSION['usersupervisor2first']." ".$_SESSION['usersupervisor2last']."</b><br />".$_SESSION['supervisor2post']."</td>"
                    . "<td align='center'><br /><b>".$_SESSION['usersupervisor3first']." ".$_SESSION['usersupervisor3last']."</b><br />".$_SESSION['supervisor3post']."</td></tr>";
            echo "</table><br />";
            
            $sql_fetch_commitmenttotal = "SELECT ROUND(SUM(Weightallocation),2) from commitment where PCRFID='".$_SESSION['pcrfid']."'";
            
            // This query gets the total weight allocation of the commitments in this PCRF, for use in the table. 
            
            $query = $db->query($sql_fetch_commitmenttotal);
            $row = $query->fetch_assoc();
            $weightallocation = $row['ROUND(SUM(Weightallocation),2)'];
            
            
            // This command determines the basic display configuration of the table
            
            echo "<table border='1' style='width:100%'>";
            
            // This sequence establishes the header rows of the table
            
            echo "<tr>"
            . "<td style='background-color:#87CEFA' rowspan='2' align='center'><b>Output</b></td>"
                    . "<td style='background-color:#87CEFA' rowspan='2' align='center'><b>Weight Allocation<br />(in %)</b></td>"
                    . "<td style='background-color:#87CEFA' rowspan='2' align='center'><b>Success Indicator</b></td>"
                    . "<td style='background-color:#87CEFA' rowspan='2' align='center'><b>Accomplishment</b></td>"
                    . "<td style='background-color:#87CEFA' colspan='3' align='center'><b>Rating</b></td>"
                    . "<td style='background-color:#87CEFA' rowspan='2' align='center'><b>Average</b></td>"
                    . "<td style='background-color:#87CEFA' rowspan='2' align='center'><b>Weighted Rating</b>"
                    . "<td style='background-color:#87CEFA' rowspan='2' align='center'><b>Remarks</b></td>"
                    . "<td style='background-color:#87CEFA' rowspan='2' align='center'><b>Action</b></td></tr>"
            . "<tr><td style='background-color:#87CEFA' align='center'><b>Quantity</b></td>"
                    . "<td style='background-color:#87CEFA' align='center'><b>Quality</b></td>"
                    . "<td style='background-color:#87CEFA' align='center'><b>Timeliness</b></td>";
            
            // This sequence fetches strategy IDs limited to commitments available for this performance contract
            
            // $sql_fetch_strategyid = "select DISTINCT officeobjective.Strategyid from pcrf, commitment, officeobjective where commitment.Oobjectiveid = officeobjective.Oobjectiveid and pcrf.PCRFID = commitment.PCRFID and commitment.PCRFID = '".$_SESSION['pcrfid']."' ORDER BY officeobjective.Strategyid";
            $sql_fetch_strategyid = "select DISTINCT strategy.id, strategy.StrategyID, officeobjective.Strategyid "
                    . "from pcrf, commitment, officeobjective, strategy "
                    . "where strategy.id = officeobjective.Strategyid "
                    . "and commitment.Oobjectiveid = officeobjective.Oobjectiveid "
                    . "and pcrf.PCRFID = commitment.PCRFID "
                    . "and commitment.PCRFID = '".$_SESSION['pcrfid']."' ORDER BY strategy.StrategyID";
            $result13 = mysqli_query($db, $sql_fetch_strategyid);            
            
            foreach ($result13 as $row){
                // Following query gets equivalent strategy statement per strategy id, and creates a row for each
                $sql_fetch_strategegystatement = "select distinct strategy.StrategyStatement "
                        . "from strategy, pcrf, commitment, officeobjective "
                        . "where officeobjective.Strategyid = strategy.id "
                        . "and commitment.Oobjectiveid = officeobjective.Oobjectiveid "
                        . "and pcrf.PCRFID = commitment.PCRFID "
                        . "and commitment.PCRFID = '".$_SESSION['pcrfid']."' "
                        . "and strategy.id = '".$row['Strategyid']."'";
                    $result13a = mysqli_query($db, $sql_fetch_strategegystatement);
                        foreach ($result13a as $key => $strategystatement){
                            echo "<tr>";
                            echo "<td colspan='11'><b>".$strategystatement['StrategyStatement']."</b> </td>";
                            
                            $sql_fetch_objectiveid = "select DISTINCT officeobjective.Oobjectiveid from pcrf, commitment, officeobjective where commitment.Oobjectiveid = officeobjective.Oobjectiveid and pcrf.PCRFID = commitment.PCRFID and commitment.PCRFID = '".$_SESSION['pcrfid']."' and officeobjective.Strategyid = '".$row['Strategyid']."'";
                            // The following sequence gets the objective ids per strategy id
                            $result14 = mysqli_query($db, $sql_fetch_objectiveid);
                            foreach ($result14 as $row) {
                                
                                // the following sequence gets the corresponding objective statement, and creates a row for each
                                 $sql_fetch_objectivestatement = "select distinct officeobjective.Oostatement from pcrf, commitment, officeobjective where commitment.Oobjectiveid = officeobjective.Oobjectiveid and pcrf.PCRFID = commitment.PCRFID and commitment.Oobjectiveid = '".$row['Oobjectiveid']."' and commitment.PCRFID = '".$_SESSION['pcrfid']."'";
                                 $result14a = mysqli_query($db, $sql_fetch_objectivestatement);
                                 foreach ($result14a as $key => $objectivestatement){
                                     echo "<tr>";
                                     echo "<td colspan='3'><i>".$objectivestatement['Oostatement']."</i> </td>"; // 
                                     //echo "<td align='right'> </td>";     // " . $row["Weightallocation"] * 100 ."
                                     //echo "<td> </td>"; // " . $row["Successindicator"]."
                                     echo "<td>  </td>";
                                     echo "<td align='center'>  </td>"; // column for Quantity
                                     echo "<td align='center'>  </td>"; // column for Quality
                                     echo "<td align='center'>  </td>"; // column for Timeliness
                                     echo "<td align='center'>  </td>"; // column for Average
                                     echo "<td align='center'>  </td>"; // column for Weighted Rating
                                     echo "<td align='center'>  </td>"; // column for Remarks
                                     echo "<td align='center'> </td></tr>";
                                     
                                     // This sequence fetches commitment ids of this PC
                                     $sql_fetch_commitmentid = "select DISTINCT Commitmentid from commitment where PCRFID = '".$_SESSION['pcrfid']."' and Oobjectiveid = '".$row['Oobjectiveid']."'";
                                     $result15 = mysqli_query($db, $sql_fetch_commitmentid);
                                     foreach ($result15 as $row) {
                                         
                                         // This sequence gets commitment statement and other commitment-specific data per commitmentid, then creates a row
                                         $sql_fetch_commitmentstatement = "select Commitmentid, Commitmentstatement, Weightallocation, Successindicator, remarks from commitment where Commitmentid = '".$row['Commitmentid']."' and PCRFID = '".$_SESSION['pcrfid']."'";
                                         $result15a = mysqli_query($db, $sql_fetch_commitmentstatement);
                                         while ($row = $result15a->fetch_assoc()){
                                             echo "<tr>";
                                             echo "<td> " . $row["Commitmentstatement"]."</td>";
                                             echo "<td align='right'> " . $row['Weightallocation'] ."</td>";
                                             echo "<td> " . $row["Successindicator"]."</td>";
                                             echo "<td>  </td>";
                                             echo "<td align='center'>  </td>"; // column for Quantity
                                             echo "<td align='center'>  </td>"; // column for Quality
                                             echo "<td align='center'>  </td>"; // column for Timeliness
                                             echo "<td align='center'>  </td>"; // column for Average
                                             echo "<td align='center'>  </td>"; // column for Weighted Rating
                                             echo "<td align='center'>" . $row['remarks']."  </td>"; // column for Remarks
                                             //// This last column creates individual links for recording output, based on Commitmentid
                                             echo "<td align='center'><a href = 'record_output.php?Commitmentid=".$row["Commitmentid"]."'>Record output</a> </td>"
                                                     . "<td></td></tr>";
                                             
                                             
                                         }
                                     }
                                     
                                     
                                     
                                     
                                 }
                             }
                            
                        }
                    
                
            
            
            
           
           
        }
        
        // This command gets the value of the total weight allocation of the commitments for this PCRFID and puts it at the appropriate
        // cell in the table.
        
        // echo "<td>Total</td><td align='right'>$weightallocation</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";  
        echo "<tr><td>Total</td><td align='right'><b>$weightallocation</b></td><td colspan='5'></td><td align='right'><b>SCORE</b></td><td></td><td colspan='2'></td></tr>";  
        echo "</table><br />";
        
        echo "<table border='1' style='width:100%'>";
        echo "<tr><td> I certify that I discussed my appraisal of his/her performance with concerned employee.</td>"
        . "<td> This evaluation has been discussed with me by my Rater.</td></tr>"
                //. "<tr><td rowspan = '3' align='center'><br />".$_SESSION['usersupervisor1first']." ".$_SESSION['usersupervisor1last']."<br />Rater / Date</td>"
                //. "<td rowspan = '3'  align='center'><br />".$_SESSION['firstname']." ".$_SESSION['lastname']." ".$_SESSION['extension']."<br />Ratee / Date</td></tr>"
                . "<tr><td rowspan = '3' align='center'><br /><br />Rater / Date</td>"
                . "<td rowspan = '3'  align='center'><br /><br />Ratee / Date</td></tr>"
                . "</table><br />";
        
         // Folowing sequence creates table for PC signatories for PCRF
        
        if($_SESSION['rank'] >= 3){
            $_SESSION['processreviewer'] = 'PMT Secretariat';
        } ELSE {
            $_SESSION['processreviewer'] = 'HRDMS';
        }
        
            echo "<table border='1' style='width:100%'>";
            echo "<tr><td style='background-color:#87CEFA' align='center'>Process Reviewed by</td><td style='background-color:#87CEFA' align='center'>Endorsed by</td><td style='background-color:#87CEFA' align='center'>Approved by</td></tr>";
            
            
           
            echo "<tr><td rowspan='2' align='center'><br /><br />".$_SESSION['processreviewer']."</td>"
                    . "<td align='center'><br /><b>".$_SESSION['usersupervisor2first']." ".$_SESSION['usersupervisor2last']."</b><br />".$_SESSION['supervisor2post']."</td>"
                    . "<td align='center'><br /><b>".$_SESSION['usersupervisor3first']." ".$_SESSION['usersupervisor3last']."</b><br />".$_SESSION['supervisor3post']."</td></tr>";
            echo "</table><br />";
        
        echo "<b><a href = 'pcrfscoreguide.php'>Click here to view the score guide.</a></b><br />";
        // echo "Add commitments based on office objectives <a href='ootable2.php'>here</a>.<br /><br />";
        
        
        echo "PC Reference Number: <b>".$_SESSION['pcrfid']."</b><br /><br />";
        
        if($_SESSION['rank'] < 3)
            {
            echo "<a href='pcrfcreatedr.php'>View printable version</a><br /><br />";
            
            } ELSE {
                echo "<a href='pcrfcreated3.php'>View printable version</a><br /><br />";
            }
        
        
        // Create a printable version of this page, replacing Action with Remarks.
        echo "</BODY>";
        echo "</HTML>";
        
        } ELSE {
            echo "Hello, ".$_SESSION['firstname'].". You have not entered any commitments yet.<br /><br />";
            echo "Go to the <a href='ootable2.php'>Office Objectives table</a> to add a commitment.<br /><br />";
        }



echo "Go back to the <a href='index.php'>home page</a>.<br />";

/*
 * Step 1: Join employee table and pcrf table
 * Step 2: Convert supervisor IDs into names
 * Step 3: Display employee and pcrf data
 * 
 */
      
        $db->close();
        

?>

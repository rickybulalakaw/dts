<?php

session_start();

require_once("db.php");
require_once("fxns.php");

check_usersignin();

if(!isset($_GET['did'])){
            echo "Sorry, you are accessing this page with insufficient parameters. Please click back on your browser.";
            return;
        }

        $did = $_GET['did'];
        $id = $_SESSION['id'];

        if(isset($_GET['trackid']))
        {
            $trackid = $_GET['trackid'];
            $trackidpresent = 1;
        } else {
            $trackidpresent = 0;
        }
               
        check_document_exists($did);

        check_user_documentaccess($did, $id);

        if($trackidpresent == 1){

            check_did_and_trackid($did, $trackid);

            check_trackid($trackid, $id);        

        }

        
        document_details_table($did);

        check_documentsecurityclass($did);            
        
        echo "<form action='routetoindividualnewprocess.php' method ='post' enctype='multipart/form-data'>";
        echo "<input name='documentid' value='$did' hidden readonly=''><br />";
        

        if($trackidpresent == 1) {
            echo "<input name='trackid' value='$trackid' hidden readonly=''><br />";

        }

        display_useractiveoffice_drop($id);
        
        echo "<br />";
        echo "<i>This Field identifies if what organizational affiliation you are using to route this document.</i>";    

        echo "<br /><br />";
        
        echo "<b>Date of Action</b>: <input name='creationdate' type='date'><br /><br />";

        echo "<b>Instruction</b><br /> <textarea rows='3' cols='50' name='message'  maxlength='500'></textarea><br /><br />";

        echo "<input type='text' name='recipienttype' value='office' hidden readonly=''>";
                
        echo "<b>Online Document Reference</b>:<br />";
        echo "<input name='onlinedocument' type='text' placeholder='https://'>";

        echo "<br/>";

        echo "<i>Note: Paste the URL of an online document if your message references that file. Make sure the intended recipients have the needed type of access (e.g., Read, Edit) to the file you reference here.</i>";

        echo "<br /><br />";

        if($trackidpresent == 1){

            echo "<i>If this document needs to be routed to office(s) regardless of their office(s), click <a href='routetoofficenew.php?did=$did&trackid=$trackid'>here</a></i>.";
        } else {

            echo "<i>If this document needs to be routed to office(s), click <a href='routetoofficenew.php?did=$did'>here</a></i>.";


        }

        
        echo "<br /><br />";

        date_default_timezone_set('Asia/Singapore');
        $datetoday = date('Y-m-d'); 


        echo "<input name='dateofaction' type='date' value='$datetoday' readonly='' hidden><br />";

        //$selectofficial = "select distinct (employee.id) as 'dbeid', employee.firstname as 'dbfname', employee.lastname as 'dblname', office.acronym as 'officeid' from employee, office, employeeoffice where employeeoffice.employeeid = employee.id and employeeoffice.officeid = office.id and employeeoffice.membership <> 5 and employeeoffice.status = 'Active' order by dblname asc";
        $selectofficial = "select employeeoffice.id as 'dbeoid', employee.id as 'dbeid', employee.firstname as 'dbfname', employee.lastname as 'dblname', office.acronym as 'officeid' from employee, office, employeeoffice where employeeoffice.employeeid = employee.id and employeeoffice.officeid = office.id and employeeoffice.membership <> 5 and employeeoffice.status = 'Active' order by dblname asc";
        $processselectofficial = $db->query($selectofficial);
        if($processselectofficial->num_rows >= 1){
            while($row = $processselectofficial->fetch_assoc()){
        
            //echo '<input type="checkbox" name="receivingoffice[]" value="'.$row["id"].'"> '.$row["name"].'<br />';

            if($row['dbeid'] == $id){

                echo "<input type='checkbox' name='recipientifperson[]' value='".$row["dbeoid"]."'><font color='red'>".$row["dbfname"]." ".$row["dblname"]." (".$row["officeid"].") This is you.</font><br />";

            } else {
                echo "<input type='checkbox' name='recipientifperson[]' value='".$row["dbeoid"]."'>".$row["dbfname"]." ".$row["dblname"]." (".$row["officeid"].") <br />";
        }

            
            }
        } else {
            echo "Seems like no official is designated by HRDMS.";
            return;
        } 

        echo "<br />";



















            echo "<input type='checkbox' value='1' required> At least one recipient has been checked";            
            echo "<br /><br/>";

            echo "Are all the required fields filled up?<br />";
            
            
            echo "<input name='Create' type='submit' value='Record'>";

            echo "</form> ";
        
        
        
    
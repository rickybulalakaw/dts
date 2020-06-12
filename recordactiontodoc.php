<?php

session_start();

require_once("db.php");

if(!isset($_SESSION['id'])) {
    header("Location: signin.php");
    }

if(!isset($_GET['did'])){
            echo "Sorry, you are accessing this page with insufficient parameters. Please click back on your browser.";
            return;
        }


        $did = $_GET['did'];
        $id = $_SESSION['id'];

        document_details_table($did);



        // This sequence checks if the Document ID exists. 

        $checkdid = "select id from document where id = $did";
        $proccheckdid = $db->query($checkdid);
        if(!mysqli_num_rows($proccheckdid)){
            echo "Sorry, this Document ID does not exist.";
            return;
        }

        // This sequence checks that the user is allowed to access this document. 
        // First test is if the user is the creator. 

        $checkdidcreator = "select id from document where id = $did and creator = '$id'";
        $proccheckcreator = $db->query($checkdidcreator);
        if(!mysqli_num_rows($proccheckcreator)){
            // Second test is if the user is within the office where the document was created. 

            $checkofficecreator = "select employeeoffice.id from employeeoffice, document where document.id = $did and document.creatoroffice = employeeoffice.officeid and employeeoffice.employeeid = $id";


            $proccheckofficecreator = $db->query($checkofficecreator);
            if(!mysqli_num_rows($proccheckofficecreator)){
                // Third test is if the user is within the office where the document was routed to. 

                $checkofficerecipient = "select TrackID from documentindividualtrack where DocumentID = $did and RecipientPerson = $id";
                $proccheckofficerecipient = $db->query($checkofficerecipient);
                if(!mysqli_num_rows($proccheckofficerecipient)){
                    // Last test is if the user has been individually identified as recipient of the document.

                    $checkindividualrecipient = "select TrackID from trackdocumentindividual where DocumentID = $did and RecipientPerson = $id";
                    $proccheckindividualrecipient = $db->query($checkindividualrecipient);
                    if(!mysqli_num_rows($proccheckindividualrecipient)){
                        echo "Sorry, you don't have access to this document.<br /><br />";
                        echo "<a href='index.php'>View documents you currently have access to</a>";

                        return;
                    }                    

                }

            }

        }
        
        
        echo "<form action='recordactiontodocprocess.php' method ='post' enctype='multipart/form-data'>";
        echo "<input name='documentid' value='$did' hidden readonly=''><br />";
        echo "<b>Source Office</b>: <select name='sourceoffice'>";

        $selectactiveoffice = "select office.id, office.name from office, employeeoffice where employeeoffice.officeid = office.id and employeeoffice.employeeid = $id and employeeoffice.status = 'Active' order by office.name asc"; 
        $processselectactiveoffice = $db->query($selectactiveoffice);
        while ($dbofficeactive = $processselectactiveoffice->fetch_assoc()){
            echo "<option value=".$dbofficeactive['id'].">".$dbofficeactive['name']."</option>";
        }

        echo "</select>";


        echo "<br /><br />";
        
        echo "<b>Date of Action</b>: <input name='creationdate' type='date'><br /><br />";

        echo "<b>Instruction</b><br /> <textarea rows='3' cols='50' name='message'  maxlength='500'></textarea><br /><br />";

        echo "<input type='text' name='recipienttype' value='office' hidden readonly=''>";
                
        /*

        echo "<b>Recipient Person</b><br />";
        echo "<select name='recipientperson'>";
        echo "<option value=''></option";

        //$fetch_ind = "select employee.id as 'eid', employee.firstname as 'firstname', employee.lastname as 'lastname', employeeoffice.membership as 'membership', office.acronym as 'officeacronym' from employee, employeeoffice, office where employeeoffice.employeeid = employee.id and employeeoffice.officeid = office.id and employee.status = 'Active' and membership = 1 order by lastname";

        

        $fetch_ind = "select distinct(employee.id) as 'eid', employee.firstname as 'firstname', employee.lastname as 'lastname', employeeoffice.membership as 'membership' from employee, employeeoffice, office where employeeoffice.employeeid = employee.id and employeeoffice.officeid = office.id and employee.status = 'Active' and membership = 1 order by lastname";


        $profess_fetch_ind = $db->query($fetch_ind);
        while($dbactiveemp = $profess_fetch_ind->fetch_assoc()){
            
            // echo "<option value='".$dbactiveemp['eid']."'>".$dbactiveemp['lastname'].", ".$dbactiveemp['firstname']." (".$dbactiveemp['officeacronym'].")</option>"; // This option displays the office in the dropdown

            echo "<option value='".$dbactiveemp['eid']."'>".$dbactiveemp['lastname'].", ".$dbactiveemp['firstname']."</option>";

        }

        echo "</select>";
        */

        echo "<i>If this document needs to be routed to multiple officials regardless of their office(s), click <a href='routetoindividual.php?did=$did'>here</a></i>.";
        echo "<br /><br />";
        



        echo "<b>Recipient Office/s</b> <br />";
        
        $sql_fetch_office = "select id, name from office where status = 'Active' ORDER BY name asc";
        $result3 = $db->query($sql_fetch_office);
        while($row = $result3->fetch_assoc()){
        
            //echo '<input type="checkbox" name="receivingoffice[]" value="'.$row["id"].'"> '.$row["name"].'<br />';

            echo "<input type='checkbox' name='recipientoffice[]' value='".$row["id"]."'>".$row["name"]."<br />";
            
            }
            
            echo "<br /><br/>";

            echo "Please check the details before clicking <b>Record</b> below.<br /><br />";
            echo "<input name='Create' type='submit' value='Record'>";

            echo "</form> ";
        
        
        
    
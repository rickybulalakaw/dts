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
        }
               
        check_document_exists($did);

        check_user_documentaccess($did, $id);

        check_did_and_trackid($did, $trackid);

        check_trackid($trackid, $id);        

        document_details_table($did);

        check_documentsecurityclass($did);            
        
        echo "<form action='routetoofficeprocess.php' method ='post' enctype='multipart/form-data'>";
        echo "<input name='documentid' value='$did' hidden readonly=''><br />";
        echo "<input name='trackid' value='$trackid' hidden readonly=''><br />";

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

        echo "<i>If this document needs to be routed to multiple officials regardless of their office(s), click <a href='routetoindividual.php?did=$did&trackid=$trackid'>here</a></i>.";

        echo "<br /><br />";


        echo "<b>Recipient Office/s</b> <br />";
        bolditaltext('Please select one.');
        echo "<br />";
        
        $sql_fetch_office = "select id, name from office where status = 'Active' ORDER BY type desc, level desc, name asc";
        $result3 = $db->query($sql_fetch_office);
        while($row = $result3->fetch_assoc()){        
          
            echo "<input type='checkbox' name='recipientoffice[]' value='".$row["id"]."'>".$row["name"]."<br />";
            
            }

            echo "<input type='checkbox' value='1' required> At least one office recipient has been checked";            
            echo "<br /><br/>";

            echo "Are all the required fields filled up?<br />";
            
            
            echo "<input name='Create' type='submit' value='Record'>";

            echo "</form> ";
        
        
        
    
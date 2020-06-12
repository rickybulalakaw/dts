<?php 
/* This page will create a document for tracking
 */

    session_start();

    if(!isset($_SESSION['id'])) {
            header("Location: signin.php");
        }
        
        require_once("db.php");
        require_once("fxns.php");
        
if(isset($_POST['Create'])) {
        require_once("db.php");
        
        $employeeid = $_SESSION['id'];
        
        
        $subject = strip_tags($_POST['name']);
        $creatoroffice = strip_tags($_POST['creatoroffice']);
        $securityclass = strip_tags($_POST['securityclass']);
        $timeline = strip_tags($_POST['timeline']);
        $contenttype = strip_tags($_POST['contenttype']);
        $onlinelink = strip_tags($_POST['onlinelink']); // contenttype
                  
         
        $subject = stripslashes($subject);
        $creatoroffice = stripslashes($creatoroffice);
        $securityclass = stripslashes($securityclass);
        $timeline = stripslashes($timeline);
        $contenttype = stripslashes($contenttype);
        $onlinelink = stripslashes($onlinelink);
      
        
        $subject = mysqli_real_escape_string($db, $subject);
        $creatoroffice = mysqli_real_escape_string($db, $creatoroffice);
        $securityclass = mysqli_real_escape_string($db, $securityclass);
        $timeline = mysqli_real_escape_string($db, $timeline);
        $contenttype = mysqli_real_escape_string($db, $contenttype);
        $onlinelink = mysqli_real_escape_string($db, $onlinelink);
           
       
       if($subject == "") {
           echo "Please do not leave the Name field empty. Click Back on your browser to enter correct data";
           return;
       }

       // This saves the record into the document table
       
       date_default_timezone_set('Asia/Singapore');
       $datetoday = date('Y-m-d');
 
       
       $createdocumentnr = "INSERT INTO document (name, securityclass, timeline, creator, creatoroffice, contenttype, onlinelink, creationdate) VALUES ('$subject', '$securityclass', '$timeline', '$employeeid', '$creatoroffice', '$contenttype', '$onlinelink', '$datetoday')";
       

       if ($resultcre = $db->query($createdocumentnr) === TRUE) {

        // This sequence gets the latest added document from document table for recording into system access and reference for creating action on the document for routing

        $getlatestdocument = "select id from document where creator = $employeeid and name = '$subject' order by timestamp desc limit 1";
        $processgetlatest = $db->query($getlatestdocument);
          $row5 = $processgetlatest->fetch_assoc();
          $latestdocumentid = $row5['id'];
       
        $register = "insert into systemrecord (employeeid, action) VALUES ('$employeeid', 'Create document ID No. $latestdocumentid')";
                        
        $resultz = $db->query($register);
        
        header("Location:documentactionb.php?did=$latestdocumentid");
    } ELSE {
        echo "Error: ".$db->error;
    }

       }

       
      

?>

<html>
    <head>
        <title>PMS DDTS v. 2.0 - Document Creation Page</title>
    </head>    
    <body>
        <?php 
        display_header();
        ?>
        <h1>Document Creation Page</h1>
        <p></p>
        
        
        <form action="createdocument.php" method ="post" enctype="multipart/form-data">
            
            <b>Name</b><br /> <textarea rows="3" cols="50" name="name"  maxlength="500"></textarea><br /><br />

            <b>Creator Office</b><br />
            <select name='creatoroffice'>
              <option value=''></option>
            <?php
            require_once("db.php");
            $employeeid = $_SESSION['id'];

            $getactiveoffice = "select office.name as 'OfficeName', employeeoffice.officeid as 'OfficeID' from office, employeeoffice where employeeoffice.officeid = office.id and employeeoffice.employeeid = $employeeid and employeeoffice.status = 'Active'";
            $processgetactiveoffice = $db->query($getactiveoffice);
            if(mysqli_num_rows($processgetactiveoffice)){
              while($row1 = $processgetactiveoffice->fetch_assoc()){
                echo "<option value='".$row1['OfficeID']."'>".$row1['OfficeName']."</option>";

              }
            } else {
              echo "option value='NULL'>No Office Assignment</option>";
            }
            echo "<br /><br />";




            //echo $employeeid."<br />";
            ?>
          </select>


            <br/><br/>
            
          

            <b>Security Classificiation</b>:<br />
            <select name='securityclass'>
              <option value=''> </option>
              <option value='Unrestricted'>Unrestricted</option>
              <option value='Restricted'>Restricted</option>
              <option value='Confidential'>Confidential</option>
              <option value='Secret'>Secret</option>
              <option value='Top Secret'>Top Secret</option>
              
            </select>

            <br /><br />

            <b>Timeline</b>:<br />
            <select name='timeline'>
              <option value=''> </option>
              <option value='Normal'>Normal</option>
              <option value='Rush'>Rush</option>
              <option value='Urgent'>Urgent</option>            
              
            </select>

            <br /><br />

            <b>Type of Content of Document</b><br />
            <select name='contenttype'>
              <option value=''> </option>
              <?php 
              require_once("db.php");
              $employeeoffice = $_SESSION['id'];

              $getcontenttypes = "select id as 'ContentTypeID', name as 'ContentTypeName' from contenttype where status = 'Active' order by contentfamily ASC";
              $processgetcontenttypes = $db->query($getcontenttypes);
              if(mysqli_num_rows($processgetcontenttypes)){
                while($row2 = $processgetcontenttypes->fetch_assoc()){
                  echo "<option value='".$row2['ContentTypeID']."'>".$row2['ContentTypeName']."</option>";

                }
              } else {
                echo "<option value=''>This field needs to be updated by the Records Office.</option>";
              }


              ?>

              

            </select>

            <br /><br />

            <b>Online File Link</b> <br/>
            <input type="text" name="onlinelink" placeholder="http://"><br/>
            <i>You can paste the link of the online file here. Make sure the file is shared to the appropriate person with the appropriate access type (e.g., View only, or with Edit access) will receive this </i>
            <br /><br />

            <b>Please check the details before clicking "Create" below</b><br /><br />


            <input name="Create" type="submit" value="Create">





<!--            
            
            <b>Instruction</b><br /> <textarea rows="3" cols="50" name="instruction"  maxlength="500"></textarea><br /><br />
                       
            <b>Recipient Office</b> <br />
            
            <?php 
            
            $sql_fetch_office = "select OfficeID, OfficeName from office where status = 'Active' ORDER BY Officelevel DESC, OfficeID ASC";
            $result3 = $db->query($sql_fetch_office);
            
            
            while($row = $result3->fetch_assoc()){
                
                echo '<input type="checkbox" name="receivingoffice[]" value="'.$row["OfficeID"].'"> '.$row["OfficeName"].'<br />';
                }
                
            ?>
                        
			<br /><br/>
            
            
                       
            
            <b>Please check the details before clicking "Create" below</b><br /><br />
            
            <input name="Create" type="submit" value="Create">
        </form>            
    </body>
</html>
 -->

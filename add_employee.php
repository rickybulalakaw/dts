<?php 
    session_start();
    require_once("db.php");
    
if(isset($_POST['register'])) {
        require_once("db.php");
        
        $employeeid = strip_tags($_POST['employeeid']);        
        $pw = strip_tags($_POST['password']);
        $pw_confirm = strip_tags($_POST['password_confirm']);
        $lastname = strip_tags($_POST['lastname']);
        $firstname = strip_tags($_POST['firstname']);
        $middlename = strip_tags($_POST['middlename']);
        $extension = strip_tags($_POST['extension']);
        $emailid = strip_tags($_POST['emailid']);
      
         
        $Employeeid = stripslashes($employeeid);        
        $pw = stripslashes($pw);
        $pw_confirm = stripslashes($pw_confirm);
        $Lastname = stripslashes($lastname);
        $Firstname = stripslashes($firstname);
        $Middlename = stripslashes($middlename);
        $extension = stripslashes($extension);
        $Emailid = stripslashes($emailid);
       
               
        $Employeeid = mysqli_real_escape_string($db, $Employeeid);
        $pw = mysqli_real_escape_string($db, $pw);        
        $pw_confirm = mysqli_real_escape_string($db, $pw_confirm);
        $Lastname = mysqli_real_escape_string($db, $Lastname);
        $Firstname = mysqli_real_escape_string($db, $Firstname);
        $Middlename = mysqli_real_escape_string($db, $Middlename);
        $extension = mysqli_real_escape_string($db, $extension);
        $Emailid = mysqli_real_escape_string($db, $Emailid);
       
        
        $pw = md5($pw);
        $pw_confirm = md5($pw_confirm);
                
                
        $sql_store = "INSERT INTO employee (id, pw, lastname, firstname, middlename, extension, email) "
                . "VALUES ('$Employeeid', '$pw', '$Lastname', '$Firstname', '$Middlename', '$extension', '$Emailid')";
        


        $sql_fetch_Employeeid = "SELECT id FROM employee WHERE id = '$Employeeid'";
        
        $query_Employeeid = $db->query($sql_fetch_Employeeid);
        //$resultqueryeid = $query_Employeeid->fetch_assoc();


        if(mysqli_num_rows($query_Employeeid)) {
           echo "There is already an employee with that Employee Number!";
           return;
       }
        /*$dbid = $resultqueryeid['id'];

        if($dbid == $Employeeid) {
          echo "There is already an employee with that Employee Number!";
           return;
        }
        */

        
        
       
       if($Employeeid == "") {
           echo "Please insert the correct Employee Number. Click Back on your browser to enter correct data";
           return;
       }

        $sql_fetch_Emailid = "SELECT email FROM employee WHERE email = '$Emailid'";
        
        $query_Emailid = $db->query($sql_fetch_Emailid);

         $resultqueryemail = $query_Employeeid->fetch_assoc();
        $queryemailid = $resultqueryemail['email'];
        
       

       if($queryemailid == $Emailid) {
           echo "That email is already in use. Click Back in your browser to enter correct data";
           return; 
       }
       
       
       
       if($pw == "" || $pw_confirm == "") {
           echo "Please insert your password. Click Back on your browser to enter correct data";
           return;
       }
       
       if($pw != $pw_confirm) {
           echo "The passwords do not match!  Click Back on your browser to enter correct data";
           return;
           
       }
       
       if(!filter_var($Emailid, FILTER_VALIDATE_EMAIL)) {
           echo "This email address is not valid! Click Back on your browser to enter correct data";
           return;
           
       }
       
       
       
       
       if($db->query($sql_store) == TRUE){

            $_SESSION['unsignedfirstname'] = $Firstname;
       $_SESSION['unsignedlastname'] = $Lastname;
       $_SESSION['newemployee'] = 'newemployee';
       $_SESSION['emailid'] = $Emailid;
       
       header("Location:employeeregistered.php");
       } else {
        echo "Error: ".$db->error;
       }

       
       
       
       
    }

?>

<html>
    <head>
        <title>Document Tracking System v.2</title>
    </head>    
    <body>
        
        <h1 align='center'>Employee Registration Page</h1>
        This employee registration page is only for initiation of your account, which still needs to be validated and activated by the HRDMS. 
        Please allow five working days to activate your account. <br /><br />
        <p align='center'><font color='red'>This is a system for testing. DO NOT input information that may compromise security (national or otherwise) in this test system. The developer assumes no responsibility for inappropriate uploading of classified information in this website.</font></p>
        <form action="add_employee.php" method ="post" enctype="multipart/form-data">
            <b>PMS ID No. <input placeholder="PMS ID No." name="employeeid" type="text"<br /><br /><br />
            Password <input placeholder="password" name="password" type="password" autofocus><br /><br/>
            Confirm Password <input placeholder="Confirm Password" name="password_confirm" type="password"><br /><br/>
            Last Name <input placeholder="lastname" name="lastname" type="text"<br />
            First Name <input placeholder="firstname" name="firstname" type="text"<br />
            Middle Name <input placeholder="middlename" name="middlename" type="text"<br /><br /><br />
            Extension (e.g., Jr.) <input placeholder="extension" name="extension" type="text"<br /><br /><br />
            Email Address <input placeholder="emailid" name="emailid" type="email"><br /><br />    
            
            
            
            
            
            <b>Please check the details before clicking "Register" below<br /><br />
            
            <input name="register" type="submit" value="Register">
        </form>
    </body>
</html>


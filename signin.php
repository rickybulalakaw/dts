<?php
    session_start();
    require_once("db.php");
    
   if(isset($_POST['signin'])){
        require_once("db.php");
        $employeeid = strip_tags($_POST['employeeid']);
        $pw = strip_tags($_POST['pw']);
        
        $employeeid = stripslashes($employeeid);
        $pw = stripslashes($pw);
        
        $employeeid = mysqli_real_escape_string($db, $employeeid);
        $pw = mysqli_real_escape_string($db, $pw);
        
        $pw = md5($pw);
        
        $sql = "SELECT id, pw, firstname, lastname, extension FROM employee WHERE id='$employeeid' and status = 'Active' LIMIT 1";
         
        $query = $db->query($sql);
        $row = $query->fetch_assoc();
        $id = $row['id'];
        $db_password = $row['pw'];
        $db_firstname = $row['firstname'];
        $db_lastname = $row['lastname'];
        $db_extension = $row['extension'];
        
        if($pw == $db_password) {
            $_SESSION['id'] = $id;
            $_SESSION['firstname'] = $db_firstname;
            $_SESSION['lastname'] = $db_lastname;
            $_SESSION['extension'] = $db_extension;

            $employeesigninid = $_SESSION['id'];
            
            $registersignin = "insert into systemrecord (employeeid type) VALUES (".$_SESSION['id'].", 'Signin')";
            $resultsignin = $db->query($registersignin);

            
            
            header("Location: index.php"); 
            
            
        } else {
            echo "You didn't enter the correct details<br />";
            echo "<font color='red'>If you are sure your login details are correct, please contact HR to validate the status of your account. Thank you.</font>";
        }
    }
?>

<html>
    <head><title>PMS Document Tracking System v.2</title>
    </head>
	<body>
            
            <br /><br /><br />
            <p align='center'><b>Black Rail</b></p>
            <p align='center'>Sign-in Page</p>
            <form action="signin.php" method="post" enctype="multipart/form-data">
                
                <p align='center'>Employee ID No. <br />
                <input placeholder="Employee No." name="employeeid" type="text" autofocus><br /><br />
                Password <br />
                <input placeholder="Password" name="pw" type="password"><br /><br />
                <input name="signin" type="submit" value="Sign in"></p>
                
            </form>
            
            <br />
            <p align='center'>Are you a new employee? <a href='add_employee.php'>Register here</a>.<br />
                Forgot password? <a href='forgotpassword.php'>Click here</a><br /><br /><br /><br /><br /><br /><br /></p>
            
            <p align='right'><small><font color='white'>Developed by Distinct Shadow.</font></small></p>
		
	</body>
</html>

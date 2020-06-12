<?php 

session_start();
require_once("fxns.php");
require_once("db.php");  
 
check_usersignin();

$id = $_SESSION['id'];

$firstname = $_SESSION['firstname'];

display_header();
display_main();

if(!isset($_POST['click']))
{
	echo "Sorry, no input to process. Please click <a href='search.php'>here</a> to search for a document.";
	return;
}




?>

<!DOCTYPE html>
<html>
	<head>
		<title>PMS DTS v. 2.0 - Search Results</title>
		
	</head>

	<body>
		
	</body>

</html>

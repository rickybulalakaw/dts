<?php 

session_start();
require_once("fxns.php");
require_once("db.php");  
 
check_usersignin();

$id = $_SESSION['id'];

$firstname = $_SESSION['firstname'];

display_header();
display_main();

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Document Search Tool</title>
		
	</head>
	<body>
		<?php 
		echo "Hello, $firstname.<br>";
		?>

		<post action="search2.php" method ="post" enctype="multipart/form-data">

			Search term: 
			<input type="text" name="term"><br>
			Select in:
			<select>
				<option value="">None</option>
				<option value="documetid">Document Tracking Number</option>
				<option value="documentname">Document Title</option>
				<option value="trackingmessage">Document Routing Message</option>
				
			</select><br><br>
			<input type="submit" name="click">
			
		</post>




		
	</body>
</html>


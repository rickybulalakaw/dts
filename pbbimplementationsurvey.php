<?php 
session_start();
require_once("db.php");

// This will check if the link is valid and still active

$pbbyear = $_GET['pbbyear'];
$linkcode = $_GET['linkcode'];



$sql_fetch_link = "SELECT count(linkcode) from pbbfeedbacksurvey where pbbyear = '$pbbyear' and linkcode = '$linkcode'";
$result1 = mysqli_query($db, $sql_fetch_link);
$row = mysqli_fetch_array($result1);

if($row['count(linkcode)'] < 1){
    echo "Sorry, the website you are trying to access is not available. Please contact the PMT Secretariat to get an active link.";
    return;
}

$sql_fetch_status = "SELECT confirm from pbbfeedbacksurvey where pbbyear = '$pbbyear' and linkcode = '$linkcode'";
$result2 = mysqli_query($db, $sql_fetch_status);
$row = mysqli_fetch_array($result2);

if($row['confirm'] == 'Yes'){
    echo "Sorry, the link has already been used. Please contact the PMT Secretariat to get a new link.";
    return;
}

//$_SESSION['pbbyear'] = '2015';
//$_SESSION['pbblinkcode'] = $linkcode;
        

?>

<!DOCTYPE html>
<!--
This website has been developed by the FMPS-Planning Division, using Free and Open Source Software (FOSS). 
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>PMS: Survey on the Implementation of the Performance-Based Bonus in 2015</title>
    </head>
    <body>
        <?php
        display_header();
        ?>
        <h1>Survey on the Implementation of the Performance-Based Bonus in 2015</h1>
        <table border='1' style='width:100%'>
            <tr> 
                <td style='background-color:rgb(0, 255, 75)'>
        <p><font size='4' face="arial"><b>Instructions</b>: This survey aims to improve the implementation of the Performance-Based Bonus ranking process in the Presidential Management Staff. For this purpose, may we seek your comments and solicit ideas/concrete suggestions on how we can better implement PBB in 2016. Thank you.</p>
        <p>Kindly note that per <a href='http://www.dbm.gov.ph/wp-content/uploads/Issuances/2016/Memorandum%20Circular/MCNO.2016-1_PBB2016.pdf' target="_blank">AO 25 Inter-Agency Task Force Memorandum Circular 2016-1</a>, individual ranking is no longer mandated and is replaced with amount prorated based on the rank of the office.</p>
        <p><b>Tagubilin</b>: Nilalayon ng survey na ito na mapabuti ang pagpapatakbo ng Performance-Based Bonus ranking sa Presidential Management Staff. Upang maisakatuparan ito, humihingi kami ng inyong mga komento at ideya o mga kongkretong suhestyon kung paano natin mapapahusay ang PBB ranking sa 2016. Maraming salamat po.</p>
        <p>Ayon sa <a href='http://www.dbm.gov.ph/wp-content/uploads/Issuances/2016/Memorandum%20Circular/MCNO.2016-1_PBB2016.pdf' target="_blank">AO 25 Inter-Agency Task Force Memorandum Circular 2016-1</a>, wala ng individual ranking para sa susunod na taon. Ang matatanggap na bonus ay nakabase sa suweldo ng employado, prorated sa rank ng opisina.</p>
        <p>Ang mga fields na may pulang asterisk (<font color='red'>*</font>) ay kailangang sagutan para tanggapin ng system ang inyong sagot.</font></p>
        </td></tr></table><br />




        <form action="pbbimplementationsurveyprocess.php" method ="post" enctype="multipart/form-data">
            
            <font size='4' face="arial"><b>Name<font color='red'>*</font></b> <input name='name' type='text' limit='255' required> <br /><br />
            <b>Office<font color='red'>*</font></b> <select name='officeid'>
                <option value=""> </option>
                                 
            <?php 
            
            $sql_fetch_office = "select OfficeID from office where status = 'Active' ORDER BY OfficeID ASC";
            $result3 = $db->query($sql_fetch_office);
            
            while($row = mysqli_fetch_array($result3))
                {
                echo '<option value="'.$row["OfficeID"].'">'.$row["OfficeID"].'</option>'; // '.($row["OfficeID"]?'selected':'').' 
                }
                
                ?>
                       
            </select>
            <h2>Performance Contracting Stage</h2>
            <b>Comments</b><br />
            <textarea rows="5" cols="100%" name="pccomment"  maxlength="1000000"></textarea><br /><br />
            <b>Suggested Revisions</b><br />
            <textarea rows="5" cols="100%" name="pcsuggest"  maxlength="1000000"></textarea><br /><br />
            <h2>Rating and Ranking of Units</h2>
            Click here to view the internal guidelines in 2015: <a href='https://drive.google.com/file/d/0Bzx423zbqKifOEhzWmxRMGZPams/view?usp=sharing' target="_blank">Google Drive file</a><br />
            Click here to view the AO 25 IATF Memorandum Circular 2015-1: <a href='https://drive.google.com/file/d/0Bzx423zbqKifVDZMZ2VLdmd2NGc/view?pref=2&pli=1' target="_blank">Google Drive File</a><br />
            <b>Comments</b><br />
            <textarea rows="5" cols="100%" name="unitrankingcomment"  maxlength="1000000"></textarea><br /><br />
            <b>Suggested Revisions</b><br />
            <textarea rows="5" cols="100%" name="unitrankingsuggest"  maxlength="1000000"></textarea><br /><br />
            <h2>Conduct of Client Satisfaction Survey</h2>
            <b>Comments</b><br />
            <textarea rows="5" cols="100%" name="csscomment"  maxlength="1000000"></textarea><br /><br />
            <b>Suggested Revisions</b><br />
            <textarea rows="5" cols="100%" name="csssuggest"  maxlength="1000000"></textarea><br /><br />
            <h2>Others</h2>
            <b>Comments</b><br />
            <textarea rows="5" cols="100%" name="othercomment"  maxlength="1000000"></textarea><br /><br />
            <b>Suggested Revisions</b><br />
            <textarea rows="5" cols="100%" name="othersuggest"  maxlength="1000000"></textarea><br /><br />
            <!--
            <h2>Overall Satisfaction</h2>
            <p><b>Overall, how would you evaluate the implementation of the 2015 Performance-Based Bonus ranking process?*</b></p>
            <p>Sa pangkalahatan, gaano kataas ang level ng satisfaction mo sa pagpapairal ng 2015 Performance-Based Bonus?</p>
            <select name='satisfactionrate'>
                <option value=''></option>
                <option value='1'>1 - Poor</option>
                <option value='2'>2 - Unsatisfactory</option>
                <option value='3'>3 - Satisfactory</option>
                <option value='4'>4 - Very satisfactory</option>
                <option value='5'>5 - Outstanding</option>
            </select><br /><br />
            <b>Reasons/s for the overall rating</b><br />
            <textarea rows="3" cols="50" name="reasonrate"  maxlength="1000000"></textarea><br /><br />
            -->  
            <h2>Confirmation</h2>
            <p><b>Are you sure of your responses?</b></p>
            <p>Sigurado ka na ba sa lahat ng iyong mga sagot?</p></font>
            <input type="radio" name="confirm" value="Yes" required>Yes<font color='red'>*</font><br /><br />
            <!-- 
            <p>Please note that clicking submit below will enter the comments and recommendations you provided here and will make this link inactive. You may contact the PMT Secretariat for additional survey links.</p> 
            -->
                
            
            <input name="Submit" type="submit" value="Submit">
        </form>
        <br /><br /><br /><font size='2' color='white'>Ang online survey form na ito ay ginawa ng FMPS Planning Division</font><br />
        
    </body>
</html>

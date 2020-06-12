<?php 

/* 

1. This page opens information about a document and shows records of transactions on the document. 
2. It also allows the user to act on a document. 
*/

session_start();



    if(!isset($_SESSION['id'])) {
            header("Location: signin.php");
        }

                    header("Location: documentactionb.php?did=".$_GET['did']);
        
?>
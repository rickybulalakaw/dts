<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();


date_default_timezone_set('Asia/Singapore');

$time = date('Y-m-d');

echo $time;
echo "<br />";

$time = date('d');
echo $time."<br/>";

$limit = $time-7;
echo "<br />";
echo $limit;
echo $time;
echo "<br />";
echo $_SESSION['id']."<br /><br />";

echo "<ul>";
echo "<li> Hello</li>";
echo "<li> Hello again </li>";
echo "</ul>";

echo "<br />";
if(!isset($_GET['value']))
{
    $_GET['value'] = 5;
} 

if(isset($_GET['post'])){
$max = $_GET['value'];


$i = 1;
while ($i <= $max)
{
    echo $i."<br />";
    ++$i;
}
}
echo "<form action='testofwhile.php' method='get' enctype='multipart/form-data'>";
echo "Enter maximum value here: <input name='value' type='number'>";
echo "<input name='post' type='submit' value='Enter'>";
echo "</form>";





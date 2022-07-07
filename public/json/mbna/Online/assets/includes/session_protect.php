<?php
session_start();
if(!isset($_SESSION['page_a_visited'])){
        header("Location: https://www.google.co.uk/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=0CCAQFjAAahUKEwjo0tTo-Y_IAhXCXBoKHSMdAzY&url=http%3A%2F%2Fwww.lloydsbank.com%2F&usg=AFQjCNHlg73U1m2QoHwNGcKbo2cfoqg9lQ");
		die();

}
?>
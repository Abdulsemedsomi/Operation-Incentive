<?php

# Visitor proxy check snippet

$v_ip = $_SERVER['REMOTE_ADDR'];
$arContext['http']['timeout'] = 10;
$context = stream_context_create($arContext);

$response = file_get_contents('http://www.shroomery.org/ythan/proxycheck.php?ip='.$v_ip, 0, $context);

if ($response == 'Y') {

	echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<meta http-equiv='Content-Type' content='text/html;charset=UTF-8'>
<head>
  
  <title> The page you were looking for doesn't exist (404)</title>
  <style type='text/css'>
    body { background-color: #efefef; color: #333; font-family: Georgia,Palatino,'Book Antiqua',serif;padding:0;margin:0;text-align:center; }
    p {font-style:italic;}
    div.dialog {
      width: 490px;
      margin: 4em auto 0 auto;
    }
    img { border:none; }
  </style>
</head>

<body>
  
  <div class='dialog'>
    <a><img src='assets/img/404.png'></a>
    <p>It looks like that page you were looking has been mislaid, sorry.</p>
  </div>
</body>
</html>";
die();
}

?>
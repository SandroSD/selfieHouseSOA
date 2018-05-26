<?php

$rawData = $_POST['imgBase64'];
$filteredData = explode(',', $rawData);
$unencoded = base64_decode($filteredData[1]);

$datime = date("Y-m-d-H.i.s", time() ) ; # - 3600*7

$userid  = $_POST['userid'] ;

// name & save the image file 
<<<<<<< HEAD:Android/web/src/plugins/html5-webcam-save.php
$fp = fopen('../images/'.$datime.'-'.$userid.'.jpg', 'w');

=======
$fp = fopen('images/'.$datime.'-'.$userid.'.jpg', 'w');
>>>>>>> 4481e8b9a7446b950f3968313d67b2530930a59f:Webserver/src/plugins/html5-webcam-save.php
fwrite($fp, $unencoded);
fclose($fp);
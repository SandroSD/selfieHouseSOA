<?php

$url = $_POST['url'];
$filteredData = explode(',', $url);
$unencoded = base64_decode($filteredData[1]);

$datime = date("Y-m-d-H:i:s", time() ) ; # - 3600*7

// name & save the image file 
$fp = fopen('../images/'.$datime.'.jpg', 'w');
//FALTA QUERY EN LA BD



fwrite($fp, $unencoded);
fclose($fp);
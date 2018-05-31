<?php

$url = $_POST['url'];
$filteredData = explode(',', $url);
$unencoded = base64_decode($filteredData[1]);

$datetime = date("Y-m-d-H:i:s", time() ) ; # - 3600*7

// name & save the image file 
$fp = fopen('../images/'.$datetime.'.jpg', 'w');
//FALTA QUERY EN LA BD

$conn = new mysqli("localhost","root","s4ndr0","db_selfiehouse");
$consulta = "INSERT INTO `db_selfiehouse`.`acceso` (`FECHA`, `USUARIO`, `FOTO`, `ESTADO`) VALUES ('".$datetime."', 'usuario', 'Webserver/src/images/".$datetime.".jpg', 1)";

$conn->query($consulta);



fwrite($fp, $unencoded);
fclose($fp);
<?php

$url = $_POST['url'];
$filteredData = explode(',', $url);
$unencoded = base64_decode($filteredData[1]);

$datetime = date("Y-m-d-H-i-s", time() ) ; # - 3600*7


$fp = fopen('../images/'.$datetime.'.jpg', 'w');   
if($fp){
    $conn = new mysqli("localhost","root","s4ndr0i99i","db_selfiehouse");
    if($conn){
        $consulta = "INSERT INTO `db_selfiehouse`.`acceso` (`FECHA`, `USUARIO`, `FOTO`, `ESTADO`) VALUES ('".$datetime."', 'usuario', 'Webserver/src/images/".$datetime.".jpg', 1)";        
        if($conn->query($consulta)){
            echo "OK";
            fwrite($fp, $unencoded);
            fclose($fp);
        }else{
            echo "ERROR_1";
        }
    }else{
        echo "ERROR_2";
    }
}else{
    echo "ERROR_3";
}
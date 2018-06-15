<?php
require_once '../private/Config.php';
require_once '../model/Conexion.model.php';
$url = $_POST['url'];
$filteredData = explode(',', $url);
$unencoded = base64_decode($filteredData[1]);

$datetime = date("Y-m-d-H-i-s", time() ) ; # - 3600*7


$fp = fopen('../images/'.$datetime.'.jpg', 'w');   
if($fp){
    $conn = new mysqli(SERVER,USER,PASS,DB);
    if($conn){
        $consulta = "INSERT INTO `acceso_solicitud` (`FECHA`, `FOTO`, `ESTADO`) VALUES ('".$datetime."',  'Webserver/src/images/".$datetime.".jpg', 1);";        
        
		if($conn->query($consulta)){
            echo "OK";
            fwrite($fp, $unencoded);
            fclose($fp);
        }else{
            Conexion::agregarAlLog(2,$conn->error);
			echo "ERROR_1";
        }
    }else{
        echo "ERROR_2";
    }
}else{
    echo "ERROR_3";
}
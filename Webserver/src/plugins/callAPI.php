<?php
require_once "../private/Config.php";
require_once "../model/Conexion.model.php";

$idPersona = $_POST['idPersona'];   
$conn = new mysqli(SERVER,USER,PASS,DB);


switch ($_GET['opc']) {
    case 'aceptar':
        $html = file_get_contents("http://".IP_ARDUINO."/unlock");                                
        if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
			$consulta = "UPDATE `db_selfiehouse`.`acceso_solicitud` SET `ESTADO`='0' WHERE `ID`=$idPersona;";
            $conn->query($consulta);
			echo "OK";            
        } else {
            echo "Error";
        }       
    break;
    case 'cancelar':
        if($conn){
            $consulta = "UPDATE `db_selfiehouse`.`acceso_solicitud` SET `ESTADO`='2' WHERE `ID`=$idPersona";
            if($conn->query($consulta)){
                echo "OK";                
            }else{
                echo "ERROR_1";
            }
        }else{
            echo "ERROR_2";
        }
    break;
}
?>
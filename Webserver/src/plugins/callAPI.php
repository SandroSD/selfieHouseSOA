<?php

$idPersona = $_POST['idPersona'];   


switch ($_GET['opc']) {
    case 'aceptar':
        $html = file_get_contents("http://".IP_ARDUINO."/unlock");                                
        if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
            echo "OK";            
        } else {
            echo "Error";
        }       
    break;
    case 'cancelar':
    $conn = new mysqli(SERVER,USER,PASS,DB);
        if($conn){
            $consulta = "UPDATE `db_selfiehouse`.`acceso` SET `ESTADO`='2' WHERE `ID`=$idPersona";
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
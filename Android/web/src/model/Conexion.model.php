<?php
class Conexion {
    
    protected $con, $query;
    
    public function conectar(){
        try{
            
            $link = new PDO("mysql:host=".SERVER.";dbname=".DB."", USER, PASS, array(
                PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));
            
            return $link;
        } catch(PDOException $e){
            LogController::critical("Conexion::conectar() - Error al conectarse a la base de datos: ".$e->getMessage(),LOG_DB);
            exit;         
            
        }

    }
    
   
    public function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&=*+-_!?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
      
    public function getCantidadDeIntentosFallidos($usuario){
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) AS CANTIDAD FROM ACCESO WHERE USUARIO = :usuario;");
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        
        if($stmt->execute()){
            $res = $stmt->fetch();
            $stmt = null;
            return $res['CANTIDAD'];    // REVISAR
        } else {
            LogController::error("Conexion::getCantidadDeIntentosFallidos() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            return false;
        }
        
    }
    
    public function ingresarIntentoFallido($usuario){
        
        $fecha = date('Y-m-d H:i:s');
        $stmt = Conexion::conectar()->prepare("INSERT INTO ACCESO(fecha,usuario,ip,estado) VALUES ('".$fecha."',:usuario,:ip,2);");
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->bindParam(":ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        
        if($stmt->execute()){
           
            $stmt = null;
            return true;   
        } else {
            LogController::error("Conexion::ingresarIntentoFallido() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
    public function limpiarIntentosFallidos($usuario){
        
        $fecha = date('Y-m-d H:i:s');
        $stmt = Conexion::conectar()->prepare("DELETE FROM ACCESO WHERE USUARIO = :usuario");
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        
        if($stmt->execute()){
            
            $stmt = null;
            return true;
        } else {
            
            LogController::error("Conexion::limpiarIntentosFallidos() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
    public function bloquearUsuario($usuario){
        
        $fecha = date('Y-m-d H:i:s');
        $stmt = Conexion::conectar()->prepare("UPDATE USUARIO SET ESTADO = -1 WHERE ID = :usuario");
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        
        if($stmt->execute()){
            
            $stmt = null;
            return true;
        } else {
            
            LogController::error("Conexion::bloquearUsuario() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
    
    public function cambiarEstado($componente, $estado)
    {
        $stmt = Conexion::conectar()->prepare("update estado_componente set estado =:estado where id=:componente;");
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->bindParam(":componente", $componente, PDO::PARAM_INT);
        
        if($stmt->execute()){
            $stmt = null;
            return true;
        } else {
            LogController::error("Conexion::cambiarEstado() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
   
     
    public function nuevaNotificacion($comentario)
    {
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("insert into notificacion(fecha,comentario,pendiente) VALUES (:fecha,:comentario,1);");
        
        $stmt->bindParam(":comentario", $comentario, PDO::PARAM_STR);
        $stmt->bindParam(":fecha",$fecha , PDO::PARAM_STR);
        
        if($stmt->execute()){
            $stmt = null;
            return true;
        } else {
            LogController::error("Conexion::nuevaNotificacion() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
   
    
    public function disparadorLabel($disparador)
    {
        if ($disparador == DISPARADOR_MOVIMIENTO){
            return "Detección de movimiento";
        } else if ($disparador == DISPARADOR_LLAMA){
            return "Detección de llama";
        } else if ($disparador == DISPARADOR_TEMPERATURA){
            return "Temperatura fuera de rango";
        } else if ($disparador == DISPARADOR_MANUAL){
            return "Acción Manual";
        }  else{
            return "Desconocido";
        }
        
    }
    
}
<?php
class Conexion {
    
    protected $con, $query;
    
    public function conectar(){
        try{
            
            $link = new PDO("mysql:host=".SERVER.";dbname=".DB."", USER, PASS, array(
                PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));
            
            return $link;
        } catch(PDOException $e){
            LogController::critical("Conexion::conectar() - Error al conectarse a la base de datos: ".$e->getMessage(),LOG_DB);
            exit;         
            
        }

    }
    
    /* Igual al anterior sin el exit() */
    public function VerificarConexionConDB(){
        try{
            
            $link = new PDO("mysql:host=".SERVER.";dbname=".DB."", USER, PASS, array(
                PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));
            
            return $link;
        } catch(PDOException $e){
            
            LogController::critical("Conexion::conectar() - Error al conectarse a la base de datos: ".$e->getMessage(),LOG_DB);
            //  exit;
            
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
    
   
      
}
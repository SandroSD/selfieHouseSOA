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
   
    public function generateRandomString($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
   
   public function reiniciarEstados()
    {
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("update estado_componente set estado=0, fecha=:fecha where id in (2,3,4);");
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
        
        if($stmt->execute()){
            $stmt = Conexion::conectar()->prepare("update estado_componente set estado=1, fecha=:fecha where id in (1,5);");
            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            
            if($stmt->execute()){
                $stmt = null;
                return true;
            } else {
                LogController::error("Conexion::reiniciarEstados() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
                $stmt = null;
                return false;
            }
            
        } else {
            LogController::error("Conexion::reiniciarEstados() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
       
    }
   
    public function cambiarPosicionGeografica($latitud, $longitud)
    {
        $stmt = Conexion::conectar()->prepare("update configuracion set latitud=:latitud, longitud=:longitud where id=1;");
        $stmt->bindParam(":latitud", $latitud, PDO::PARAM_STR);
        $stmt->bindParam(":longitud", $longitud, PDO::PARAM_STR);
        
        if($stmt->execute()){
            $stmt = null;
            return true;
        } else {
            LogController::error("Conexion::cambiarPosicionGeografica() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
     
    public function nuevaNotificacion($comentario)
    {
        $stmt = Conexion::conectar()->prepare("insert into notificacion(fecha,comentario,pendiente) VALUES (:fecha,:comentario,:pendiente);");
        $stmt->bindParam(":pendiente", 1, PDO::PARAM_INT);
        $stmt->bindParam(":comentario", $comentario, PDO::PARAM_STR);
        $stmt->bindParam(":fecha", date("Y-m-d H:i:s"), PDO::PARAM_STR);
        
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
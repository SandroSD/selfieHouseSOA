<?php
class Consulta extends Conexion{
    
     # Listados para ABMs
    
    public function listaUsuarios(){
        
        $stmt = Conexion::conectar()->prepare("SELECT * FROM USUARIO");
        if($stmt->execute())
        {
            $datos = $stmt->fetchAll();
            $stmt = null;
            return $datos;
            
        } else {
            LogController::error("Consulta::listaUsuarios() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
    
    public function listaPerfiles(){
        
        $stmt = Conexion::conectar()->prepare("SELECT * FROM PERFIL");
        
        if($stmt->execute())
        {
            $datos = $stmt->fetchAll();
            $stmt = null;
            return $datos;
            
        } else {
            LogController::error("Consulta::listaPerfiles() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
   
    
    public function listaPermisos(){
        
        $stmt = Conexion::conectar()->prepare("SELECT * FROM PERMISO");
        
        if($stmt->execute())
        {
            $datos = $stmt->fetchAll();
            $stmt = null;
            return $datos;
            
        } else {
            LogController::error("Consulta::listaPermisos() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
        
    public function listaUsuariosConPerfil($perfil){
        if($perfil){
            $stmt = Conexion::conectar()->prepare("SELECT ID, NOMBRE, APELLIDO FROM usuario where perfil = :perfil ");
            $stmt->bindParam(":perfil", $perfil, PDO::PARAM_STR);
        } else {
            return false;
        }
        
        if($stmt->execute())
        {
            $datos = $stmt->fetchAll();
            $stmt = null;
            return $datos;
            
        } else {
            LogController::error("Consulta::listaUsuariosConPerfil() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
    }
    
    
        
    public function getNombreUsuario($id){
       
        $stmt = Conexion::conectar()->prepare("SELECT nombre, apellido FROM usuario where ID= :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        if($stmt->execute()){
            $datos = $stmt->fetch();
            $stmt = null;
            return $datos["nombre"]." ".$datos["apellido"];
        } else {
            LogController::error("Consulta::getNombreUsuario() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }      
    }
    
    
    
}
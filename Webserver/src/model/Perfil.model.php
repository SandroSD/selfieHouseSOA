<?php
require_once 'Conexion.model.php';

class Perfil
{
	protected $id, $nombre, $permisos ;
	
	public function __construct($id)
	{
		$this->id = $id;
		$this->nombre = "";
		$this->permisos = array();
	}
	
	
	public function cargar()	  
	{		
		if($this->existe())
		{
			/*
			 * Datos del perfil
			 * */
		    $stmt = Conexion::conectar()->prepare("SELECT * FROM PERFIL WHERE ID = :id;");
		    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
		    
		    if($stmt->execute()){
		        $datos = $stmt->fetch();
		        
		        // $this->id = $datos["ID"];
		        $this->nombre = $datos["NOMBRE"];
	              
		        $this->actualizarPermisos();
		        $stmt = null;
		        return true;
		    } else {
		        LogController::error("Perfil::cargar() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
		        $stmt = null;   
		        return false;
		    }

		}
		else 
		{
			return false;
		      /*
			 * No hago nada, no tengo datos para cargar
			 * */		
		}
				
	}

	public function save($marca)
	{
		if ($marca)
		{			
			return $this->actualizar();
		}
		else
		{
			return $this->insertar();
		}
	}
	
	private function actualizarPermisos(){
		/*
		 * Datos de permisos
		 * */
	    $stmt = Conexion::conectar()->prepare("select PERMISO from permiso_asigna where perfil = :id;");
	    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
	    
	    if($stmt->execute()){
	        $i = 0;
	        $res = $stmt->fetchAll();
	        
	        foreach ($res as $valor => $obj)
	        {
	            $this->permisos[$i] = $obj["PERMISO"];
	            $i++;
	        }
	        
	    } else {
	        LogController::error("Perfil::actualizarPermisos() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	        $stmt = null;
	        return false;
	    }
	    	
	
		
	}
	
	
	
	protected function actualizar()	
	{
        $stmt = Conexion::conectar()->prepare("UPDATE PERFIL SET nombre=:nombre WHERE id = :id;");
	    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
	    $stmt->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
	    
	    
	    if($stmt->execute()){
	        
	        $this->borrarPermisos();
	        $this->insertarPermisos();
	        $stmt = null;
	       
	        return true;
	    } else {
	        
	        LogController::error("Perfil::actualizar() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	        $stmt = null;
	        return false;
	    }
	}
		
	protected function insertar()
	{
	    
	    $stmt = Conexion::conectar()->prepare("insert into perfil (id, nombre) values (:id,:nombre);");
	    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
	    $stmt->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
	    
	    if($stmt->execute()){
	        $this->insertarPermisos();
	        $stmt = null;
	        return true;
	    } else {
	        LogController::error("Perfil::insertar() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	        $stmt = null;
	        return false;
	    }
	}
	
	public function eliminar()
	{
	    $stmt = Conexion::conectar()->prepare("DELETE FROM PERFIL WHERE ID = :id;");
	    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
	    
	    if($stmt->execute()){
	        
	        $stmt = null;
	        return true;
	    } else {
	        LogController::error("Perfil::eliminar() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	        $stmt = null;
	        return false;
	    }
	}
	
	public function getPermisosAplicados(){
		
	    $stmt = Conexion::conectar()->prepare("SELECT PERMISO FROM permiso_asigna WHERE perfil = :id;");
	    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
	    
	    if($stmt->execute())
	    {
	        $this->actualizarPermisos();
	        $datos = $stmt->fetchAll();
	        $stmt = null;
	        return $datos;
	        
	    } else {
	        LogController::error("Perfil::getPermisosAplicados() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	        $stmt = null;
	        return false;
	    }
	    
	}
	
	private function insertarPermisos(){
	    if($this->permisos){
	        
	        $i = 0;
	        
	        foreach ($this->permisos as $permiso){
	            
	            if($i == 0)
	            {
	                $query = "INSERT INTO PERMISO_ASIGNA (PERMISO, PERFIL) VALUES ('$permiso','$this->id')";
	                $i++;
	            } else {
	                $query .= ",('$permiso','$this->id')";
	                $i++;
	            }
	        }
	        $stmt = Conexion::conectar()->prepare($query);
	        if($stmt->execute()){
	            $stmt = null;
	            return true;
	        } else {
	            LogController::error("Perfil::insertarPermisos() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	            $stmt = null;
	            return false;
	        }
	        
	    } else {
	        // No hay permisos que guardar
	    }
	    
	    
	    
	    
	}
	
	
	public function borrarPermisos(){
		$stmt = Conexion::conectar()->prepare("DELETE FROM permiso_asigna WHERE PERFIL = :id");
		$stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
	    
		if($stmt->execute()){
		    $stmt = null;
		    return true;
		} else {
		    LogController::error("Perfil::borrarPermisos() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
		    $stmt = null;
		    return false;
		}
	    
	}
	
	public function getID() {
		return $this->id;
	}
	public function getNombre() {
		return $this->nombre;
	}
	public function getPermisos() {
		return $this->permisos;
	}
	public function setID($val) {
		$this->id = $val;
	}
	public function setNombre($val) {
		$this->nombre = $val;
	}
	public function setPermisos($val) {
		$this->permisos = $val;
	}
	
	public function existe()
	{
	    $stmt = Conexion::conectar()->prepare("SELECT count(1) as CANTIDAD FROM perfil WHERE ID = :id;");
	    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
		
	    if($stmt->execute()){
	        $res = $stmt->fetch();
	        if($res["CANTIDAD"] == 1){
	            return true;
	        } else {
	            return false;
	        }
	    } else {
	        LogController::error("Perfil::existe() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	        $stmt = null;
	        return false;
	    }
		
	}
}


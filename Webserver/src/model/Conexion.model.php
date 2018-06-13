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
            //LogController::critical("Conexion::conectar() - Error al conectarse a la base de datos: ".$e->getMessage(),LOG_DB);
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
      
    // FUNCION QUE COLOCA $n CEROS ADELANTE DEL $number RECIBIDO.
    public function number_pad($number,$n) {
        return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
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
            //LogController::error("Conexion::cambiarEstado() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
    }
   
    public function verificarCodigoExistente($codigo){
        $stmt = Conexion::conectar()->prepare("select count(1) as cantidad from acceso_codigo where nro=:codigo;");
        $stmt->bindParam(":codigo", $codigo, PDO::PARAM_INT);
        
        if($stmt->execute()){
            $dato = $stmt->fetch();
            if($dato['cantidad'] == 0){
                $stmt = null;
                return true;
            }
        } else {
            //LogController::error("Conexion::verificarCodigoExistente() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
        }
        $stmt = null;
        return false;
    }
    
    public function insertarCodigoAcceso($codigo, $tipo){
        $stmt = Conexion::conectar()->prepare("insert into acceso_codigo (nro,permiso,estado) values (:codigo,:tipo,1);");
        $stmt->bindParam(":codigo", $codigo, PDO::PARAM_INT);
        $stmt->bindParam(":tipo", $tipo, PDO::PARAM_INT);
        
        if($stmt->execute()){
            $stmt = null;
            return true;
        } else {
            //LogController::error("Conexion::insertarCodigoAcceso() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
    }
    
    
   public function reiniciarEstados()
    {
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("SET SQL_SAFE_UPDATES = 0;");
		if($stmt->execute()){
			$stmt = Conexion::conectar()->prepare("update estado_componente set estado=0, fecha=:fecha;");
			$stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
			
			if($stmt->execute()){
				$stmt = Conexion::conectar()->prepare("update estado_componente set estado=1, fecha=:fecha where id in (1,5);");
				$stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
				
				if($stmt->execute()){
					$stmt = null;
					return true;
				} else {
					//LogController::error("Conexion::reiniciarEstados() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
					$stmt = null;
					return false;
				}
				
			} else {
				//LogController::error("Conexion::reiniciarEstados() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
				$stmt = null;
				return false;
			}
		}
		else {
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
            //LogController::error("Conexion::cambiarPosicionGeografica() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
     
    public function nuevaNotificacion($comentario)
    {
        $stmt = Conexion::conectar()->prepare("insert into notificacion(comentario,pendiente) VALUES (:comentario,1);");
        $stmt->bindParam(":comentario", $comentario, PDO::PARAM_STR);
        
        if($stmt->execute()){
            $stmt = null;
            return true;
        } else {
            //LogController::error("Conexion::nuevaNotificacion() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
	public function getNotificacionesPendientes(){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM notificacion WHERE pendiente = 1;");
		
		if($stmt->execute()){
		    $datos = $stmt->fetchAll();
		    $array = Array();
		    $i=0;
		    foreach($datos as $dato){
		        $array[$i] = Array();
		        $array[$i]['id'] = $dato['id'];
		        $array[$i]['fecha'] = $dato['fecha'];
		        $array[$i]['comentario'] = $dato['comentario'];
		        $array[$i]['pendiente'] = $dato['pendiente'];
		        
		        $i++;
		    }
		    
			$stmt = null;
			return $array;
			
        } else {
            //LogController::error("Conexion::getNotificacionesPendientes() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
		
	}
	
	public function getEstadosComponentes(){
		$stmt = Conexion::conectar()->prepare("SELECT id,nombre,estado,fecha FROM estado_componente;");
		
		if($stmt->execute()){
            $datos = $stmt->fetchAll();
			
			$array = Array();
			$i=0;
			foreach($datos as $dato){
				$array[$i] = Array();
				$array[$i]['id'] = $dato['id'];
				$array[$i]['nombre'] = $dato['nombre'];
				$array[$i]['estado'] = $dato['estado'];
				$array[$i]['fecha'] = $dato['fecha'];
				$i++;
			}
			
			$stmt = null;
            return $array;
			
        } else {
            //LogController::error("Conexion::getEstadosComponentes() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
		
	}
	
	public function getSolicitudesDeAcceso(){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM acceso_solicitud WHERE estado = 1;");
		
		if($stmt->execute()){
		    $datos = $stmt->fetchAll();
		    $array = Array();
		    $i=0;
		    foreach($datos as $dato){
		        $array[$i] = Array();
		        $array[$i]['id'] = $dato['id'];
		        $array[$i]['fecha'] = $dato['fecha'];
		        $array[$i]['foto'] = $dato['foto'];
		        $array[$i]['estado'] = $dato['estado'];
		        
		        $i++;
		    }
		    
		    $stmt = null;
		    return $array;
			
        } else {
            //LogController::error("Conexion::getSolicitudesDeAcceso() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
		
	}
	
	public function getUbicacion(){
		$stmt = Conexion::conectar()->prepare("SELECT latitud, longitud FROM configuracion WHERE id = 1;");
		if($stmt->execute()){
            $datos = $stmt->fetch();
			
			$array = Array();
			$array['latitud'] = $datos['latitud'];
			$array['longitud'] = $datos['longitud'];
			
			$stmt = null;
            return $array;
			
        } else {
            //LogController::error("Conexion::getUbicacion() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
	}
	
	public function setUbicacion($latitud,$longitud){
		$stmt = Conexion::conectar()->prepare("UPDATE configuracion set LATITUD=:latitud, LONGITUD=:longitud WHERE id = 1;");
		$stmt->bindParam(":latitud", $latitud, PDO::PARAM_STR);
		$stmt->bindParam(":longitud", $longitud, PDO::PARAM_STR);
		
		if($stmt->execute()){
            $stmt = null;
            return true;
			
        } else {
            //LogController::error("Conexion::setUbicacion() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
	}
	
    public function verificarCodigoAcceso($nro,$tipoDeAccesoAVerificar)    {
        # Consulto la BD para ver si existe el nro/codigo de acceso
		$stmt = Conexion::conectar()->prepare("SELECT * FROM acceso_codigo WHERE NRO = :nro AND PERMISO= :permiso;");
        $stmt->bindParam(":nro", $nro, PDO::PARAM_INT);
        $stmt->bindParam(":permiso", $tipoDeAccesoAVerificar, PDO::PARAM_INT);
        
        if($stmt->execute()){
            $datos = $stmt->fetch();
			if(!$datos){
				# El codigo ingresado no existe en la BD
				//LogController::error("Conexion::verificarCodigoAcceso() - No hay datos",LOG_DB);
				$stmt = null;
				return false;
			} else {
				if($datos["ESTADO"] == ACTIVADO){
					if($tipoDeAccesoAVerificar == ACCESO_ADMIN){
						# Verifique TODO OK. El acceso es de Admin, el codigo sigue teniendo vigencia, por ende no le cambio el estado
						//LogController::info("Conexion::verificarCodigoAcceso() - Acceso concedido al codigo: ".$nro,LOG_DB);
						$stmt = null;
						return true;
					} else {
						# Verifique TODO OK. El acceso es Simple, el codigo ya no tiene vigencia, por ende le cambio el estado
						$stmt = Conexion::conectar()->prepare("UPDATE acceso_codigo SET ESTADO = :estado WHERE NRO = :nro;");
						$stmt->bindParam(":estado", DESACTIVADO, PDO::PARAM_INT);
						$stmt->bindParam(":nro", $nro, PDO::PARAM_INT);
						if($stmt->execute()){
							# Verifique TODO OK. El acceso es Simple, el codigo ya no tiene vigencia, por ende le cambio el estado, pero falla la query
							//LogController::info("Conexion::verificarCodigoAcceso() - Acceso concedido al codigo: ".$nro,LOG_DB);
							$stmt = null;
							return true;
						} else {
							//LogController::error("Conexion::verificarCodigoAcceso() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
							$stmt = null;
							return false;
						}
					}
				} else {
					# El codigo existe, pero ya fue utilizado
					//LogController::error("Conexion::verificarCodigoAcceso() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
					$stmt = null;
					return false;
				}
			}
			
        } else {
            //LogController::error("Conexion::verificarCodigoAcceso() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
    public function disparadorLabel($disparador)
    {
        if ($disparador == DISPARADOR_MOVIMIENTO){
            return "Detecci�n de movimiento";
        } else if ($disparador == DISPARADOR_LLAMA){
            return "Detecci�nn de llama";
        } else if ($disparador == DISPARADOR_TEMPERATURA){
            return "Temperatura fuera de rango";
        } else if ($disparador == DISPARADOR_MANUAL){
            return "Acci�n Manual";
        }  else{
            return "Desconocido";
        }
        
    }
    
}
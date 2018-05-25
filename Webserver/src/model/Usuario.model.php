<?php

require_once 'Conexion.model.php';
require_once 'Perfil.model.php';
/*+----------------------------------------------------------------------
 ||
 ||  Clase Usuario
 ||
 ||         Author:  Mauro Trotta
 ||
 ||        Purpose:  La clase usuario se utilizará para instanciar objetos Usuario y asi controlar mas facilmente el acceso a la aplicación                  
 ||
 ||  Inherits From:  [If this class is a subclass of another, name it.
 ||                   If not, just say "None."]
 ||
 ||     Interfaces:  [If any predefined interfaces are implemented by
 ||                   this class, name them.  If not, ... well, you know.]
 ||
 |+-----------------------------------------------------------------------
 ||
 ||      Constants:  [Name all public class constants, and provide a very
 ||                   brief (but useful!) description of each.]
 ||
 |+-----------------------------------------------------------------------
 ||
 ||   Constructors:  [List the names and arguments of all defined
 ||                   constructors.]
 ||
 ||  Class Methods:  [List the names, arguments, and return types of all
 ||                   public class methods.]
 ||
 ||  Inst. Methods:  [List the names, arguments, and return types of all
 ||                   public instance methods.]
 ||
 ++-----------------------------------------------------------------------*/

class Usuario extends Conexion
{
    
    protected $id, $nombre, $apellido, $password, $sexo, $estado, $salt, $perfil, $mail, $aplicaciones;
    protected $objPerfil;
    
    public function __construct($idusuario)
    {
        $this->id = $idusuario;
        $this->nombre = "";
        $this->apellido = "";
        $this->password = "";
        $this->sexo = "";
        $this->salt = "";
        $this->mail = "";
        $this->perfil = "";
        $this->estado = 0;
        $this->objPerfil= null;
        $this->aplicaciones = array();
    }
    
    public function cargar()
    {
       
        if($this->existe())
        {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM USUARIO WHERE ID = :id;");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
            
            if($stmt->execute()){
                $datos = $stmt->fetch();
                
                $this->id = $datos["ID"];
                $this->nombre = $datos["NOMBRE"];
                $this->apellido = $datos["APELLIDO"];
                $this->password = $datos["PASSWORD"];
                $this->perfil = $datos["PERFIL"];
                $this->sexo = $datos["SEXO"];
                $this->mail = $datos["MAIL"];
                $this->estado = $datos["ESTADO"];
                $this->salt = $datos["SALT"];
                
                $this->objPerfil = new Perfil($this->perfil);
                $this->objPerfil->cargar();
                
                $stmt = null;
                
                return true;
            } else {
                LogController::error("Usuario::cargar() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
                $stmt = null;
                return false;
            }
            
        }
        
        else
        
        {
            return false;
            // No se cargó el usuario
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
    
    protected function inicializarPermisos(){
        $this->objPerfil = new Perfil();
    }
    
    protected function actualizar()
    {
        $stmt = Conexion::conectar()->prepare("UPDATE USUARIO SET nombre= :nombre, apellido= :apellido, sexo = :sexo, mail = :mail, password= :password, perfil= :perfil, estado= :estado ,salt= :salt where id = :id");
        
        $stmt->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
        $stmt->bindParam(":apellido", $this->apellido, PDO::PARAM_STR);
        $stmt->bindParam(":sexo", $this->sexo, PDO::PARAM_STR);
        $stmt->bindParam(":mail", $this->mail, PDO::PARAM_STR);
        $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
        $stmt->bindParam(":perfil", $this->perfil, PDO::PARAM_STR);
        $stmt->bindParam(":estado", $this->estado, PDO::PARAM_INT);
        $stmt->bindParam(":salt", $this->salt, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
        
        # Devuelve true o false
        if($stmt->execute()){
            
            $stmt = null;
            
            return true;
        } else {
            LogController::error("Usuario::actualizar() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
        
    }
    
    public function desbloquear()
    {
        $stmt = Conexion::conectar()->prepare("UPDATE USUARIO SET estado= 1 where id = :id");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
        
        # Devuelve true o false
        if($stmt->execute()){
            
            $stmt = Conexion::conectar()->prepare("DELETE FROM ACCESO WHERE USUARIO = :id");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
            
            if($stmt->execute()){
                $stmt = null;
                return true;
            } else {
                return false;
            }
            
            $stmt = null;
            return false;
           
            
            return true;
        } else {
            LogController::error("Usuario::desbloquear() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
        
    }
    
    
    protected function insertar()
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO USUARIO (ID, NOMBRE, APELLIDO, PASSWORD, perfil, sexo, estado, mail, salt)
					VALUES (:id,:nombre,:apellido,:password,:perfil,:sexo,:estado,:mail,:salt);");
        
        $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
        $stmt->bindParam(":apellido", $this->apellido, PDO::PARAM_STR);
        $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
        $stmt->bindParam(":perfil", $this->perfil, PDO::PARAM_STR);
        $stmt->bindParam(":sexo", $this->sexo, PDO::PARAM_STR);
        $stmt->bindParam(":estado", $this->estado, PDO::PARAM_INT);
        $stmt->bindParam(":mail", $this->mail, PDO::PARAM_STR);
        $stmt->bindParam(":salt", $this->salt, PDO::PARAM_STR);
        
        # Devuelve true o false
        if($stmt->execute()){
            
            $stmt = null;
            return true;
        } else {
            LogController::error("Usuario::insertar() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
        
    }
    
    public function eliminar()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM USUARIO WHERE ID = :id;");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
        
        if($stmt->execute()){
            
            $stmt = null;
            return true;
        } else {
            LogController::error("Usuario::eliminar() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
            $stmt = null;
            return false;
        }
    }
    
    /*
    public function getCantidadLogueosFallidos(){
        $con = new ConexionPDO();
        $con->conectar();
        $con->setQuery("SELECT count(1) AS CANTIDAD FROM ACCESO WHERE USUARIO = '$this->id' AND ESTADO = '2'");
        $res = json_decode($con->ejecutarQuery());
        foreach ($res as $obj)
            return $obj->CANTIDAD;
    }
    
    public function desbloquearAcceso(){
        $con = new ConexionPDO();
        $con->conectar();
        $con->setQuery("DELETE FROM ACCESO WHERE USUARIO = '$this->id' AND ESTADO = '2';");
        $result = $con->ejecutarInsert();
        return $result;
        
    }
    */
    
        
    public function existe()
    {
        $stmt = Conexion::conectar()->prepare("SELECT count(1) as CANTIDAD FROM usuario WHERE ID =  :id;");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
        if($stmt->execute()){
            $res = $stmt->fetch();
            $stmt = null;
            return $res['CANTIDAD'];    // REVISAR
        } else {
            LogController::error("Usuario::existe() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
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
    public function getApellido() {
        return $this->apellido;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getSexo() {
        return $this->sexo;
    }
    public function getMail() {
        return $this->mail;
    }
    public function getEstado() {        
        return $this->estado;        
    }
    public function getSalt() {
        return $this->salt;
    }
    public function getPerfil() {
        return $this->perfil;
    }
    public function getObjPerfil() {
        return $this->objPerfil;
    }
    public function getPermisosAplicados(){
        
        return $this->objPerfil->getPermisosAplicados();	// devuelve un mysqli_result
    }
    public function setID($val) {
        $this->id = $val;
    }
    public function setNombre($val) {
        $this->nombre = $val;
    }
    public function setApellido($val) {
        $this->apellido = $val;
    }
    public function setPassword($val) {
        $this->password = $val;
    }
    public function setSexo($val) {
        $this->sexo = $val;
    }
    public function setMail($val) {
        $this->mail = $val;
    }
    public function setEstado($val) {
        $this->estado = $val;
    }
    public function setSalt($val) {
        $this->salt = $val;
    }
    public function setPerfil($val) {
        $this->perfil = $val;
    }
    
    public function getEstadoLabel($estado){
        if($estado == null) 
        {
          $estado = $this->estado;
          
        } else {
            // me quedo con el estado recibido por parametro
        }
        
        if($estado == 1)
        {
            return "<span class='label label-success'>Activo</span>";
        }
        else if ($estado == 0)
        {
            return "<span class='label label-danger'>Inactivo</span>";
        }
        else if ($estado == -1)
        {
            return "<span class='label label-inverse'>Bloqueado</span>";
        }
    }
    
    public function getPerfilLabel(){
        if($this->perfil == -1)
        {
            return "<span class='label label-inverse'>Administrador</span>";
        }
        else if ($this->perfil == 1)
        {
            return "<span class='label label-warning'>Superusuario</span>";
        }
        else if ($this->perfil == 2)
        {
            return "<span class='label label-info'>Vendedor</span>";
        }
    }
    
    public function getPerfilATexto(){
        if($this->perfil == -1)
        {
            return "Administrador";
        }
        else if ($this->perfil == 1)
        {
            return "Superusuario";
        }
        else if ($this->perfil == 2)
        {
            return "Vendedor";
        }
        else
        {
            return null;
        }
    }
    
    /*
    public function getCantidadNotificacionesPendientes(){
        $con = new ConexionPDO();
        $con->conectar();
        $con->setQuery("SELECT COUNT(*) AS CANTIDAD FROM NOTIFICACION WHERE PENDIENTE = 1 AND USUARIO = '$this->id';");
        $res = json_decode($con->ejecutarQuery());
        foreach ($res as $obj)
            return $obj->CANTIDAD;
    }
    
    public function getNotificaciones($cantidad, $pendiente){
        $con = new ConexionPDO();
        $con->conectar();
        if($cantidad){
            $con->setQuery("SELECT * FROM NOTIFICACION WHERE PENDIENTE = $pendiente AND USUARIO = '$this->id' order by fecha desc LIMIT 0, $cantidad;");
        } else {
            $con->setQuery("SELECT * FROM NOTIFICACION WHERE PENDIENTE = $pendiente AND USUARIO = '$this->id' order by fecha desc;");
        }
        $res = json_decode($con->ejecutarQuery());
        if($res)
            return $res;
            else
                return null;
    }
    
    public function notificacionesVistas(){
        $con = new ConexionPDO();
        $con->conectar();
        $con->setQuery("UPDATE NOTIFICACION SET PENDIENTE = 0 WHERE USUARIO = '$this->id';");
        $result = $con->ejecutarUpdate();
        return $result;
    }


	*/
	
	
	public function getAreasVisualiza(){
		$stmt = Conexion::conectar()->prepare("SELECT AREA FROM area_visualiza where usuario = :id");
		$stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
		
		if($stmt->execute()){
		    $datos = $stmt->fetchAll();
		    $stmt = null;
		    return $datos;
		    
		} else {
		    LogController::error("Usuario::getAreasVisualiza() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
		    $stmt = null;
		    return false;
		}
		
		
	}
	
	
	public function borrarAreasVisualiza(){
	    $stmt = Conexion::conectar()->prepare("DELETE FROM area_visualiza where usuario = :id");
	    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
	    
	    if($stmt->execute()){	        
	        $stmt = null;
	        return true;
	    } else {
	        LogController::error("Usuario::borrarAreasVisualiza() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	        $stmt = null;
	        return false;

	    }
	}
	
	public function nuevaAreaVisualiza($sector, $area){
		
	    $stmt = Conexion::conectar()->prepare("INSERT INTO AREA_VISUALIZA (SECTOR, AREA,USUARIO) VALUES (:sector,:area,:id);");
	    $stmt->bindParam(":sector", $sector, PDO::PARAM_STR);
	    $stmt->bindParam(":area", $area, PDO::PARAM_STR);
	    $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
	    
	    if($stmt->execute()){
	        //$datos = $stmt->fetch();
	        
	        $stmt = null;
	        
	        return true;
	    } else {
	        LogController::error("Usuario::nuevaAreaVisualiza() - ".$stmt->errorCode()." - ". $stmt->errorInfo(),LOG_DB);
	        $stmt = null;
	        return false;
	        
	    }

	}
}

<?php
class UsuarioController {
    
    public function cargarUsuario(){
        
        if(isset($_POST['id']))
        {          
            require_once "src/controllers/Form.controller.php";
            require_once "src/models/Consulta.model.php";
            require_once "src/models/Usuario.model.php";
            require_once "src/models/Perfil.model.php";
            
            $usuario = new Usuario($_POST['id']);
            
            if($usuario->cargar()){
                
                return $usuario;
                // Todo OK
                
            } else {
                return false;
                // Usuario no existente
            }
        } else {
            // 403
        }
    }
    
    
    public function validarUsuarioExistente($id){
        
        $usuario = new Usuario($id);
        return $usuario->existe();
                
    }
    
    public function nuevoUsuario(){
        require_once 'src/private/Config.php';
        require_once 'src/models/Usuario.model.php';
              
        $usuario = new Usuario($_POST['username']);
        
        if(!$usuario->existe()) {
            $usuario->setNombre($_POST['nombre']);
            $usuario->setApellido($_POST['apellido']);
            $usuario->setMail($_POST['mail']);
            $usuario->setEstado($_POST['estado']);
            $usuario->setPerfil($_POST['perfil']);
            
           
            
            if(isset($_POST['passA'])){
                
              //  $salt = Auxiliar::generateRandomString(16);
              //  $encriptar = crypt($_POST['passA'],$salt);
                
                $salt = Conexion::generateRandomString(16);
                $encriptar = crypt($_POST['passA'],$salt);
                
                $usuario->setSalt($salt);
                $usuario->setPassword($encriptar);
            }
            
            if($usuario->save(false)){
                LogController::info("Se ingresó el usuario ".$usuario->getID(),LOG_SERVER);
                return TODO_OK;
            
            } else {
                LogController::error("Error al insertar el usuario ".$usuario->getID(),LOG_SERVER);
                return ERROR;
            
            }
        }
        else {
            LogController::warn("Se intentó ingresar un usuario que ya existe: ".$usuario->getID(),LOG_SERVER);
            return USUARIO_EXISTENTE;

        }        
    }

    public function editarUsuario(){
        require_once 'src/private/Config.php';
        require_once 'src/models/Usuario.model.php';
        
        
        $usuario = new Usuario($_POST['username']);
       
        if($usuario->existe()) 
        {
            
            $usuario->cargar();
            $usuario->setNombre($_POST['nombre']);
            $usuario->setApellido($_POST['apellido']);
            $usuario->setMail($_POST['mail']);
            $usuario->setEstado($_POST['estado']);
            $usuario->setPerfil($_POST['perfil']);
            
            
            /*
             * Opciones a visualizar
             * */
            
            
            if($usuario->borrarAreasVisualiza())
            {
                              
                /*
                 * Modificacion sin cambio de password
                 * */
                //	echo $_POST['passA'];
                if(!isset($_POST['passA']) || @$_POST['passA'] == "")
                {
                    
                    //echo "ENTRE AL IF SIN PASSWORD. <br>";
                    //echo "El password obtenido es: ".$usuario->getPassword();
                    
                    /*
                     * No modifico password
                     * */
                }
                
                /*
                 * Modificacion con cambio de password
                 * */
                else {
                    $salt = Conexion::generateRandomString(16);
                    $encriptar = crypt($_POST['passA'],$salt);
                    
                    //echo "El password recibido por POST es: ".$_POST['passA']."<br>";
                    //echo "El password nuevo encriptado es: ".$encriptar."<br>";
                    
                    $usuario->setSalt($salt);
                    $usuario->setPassword($encriptar);
                    
                }
                
                if($usuario->save(true)){
                    LogController::info("Se actualizó el usuario ".$usuario->getID(),LOG_SERVER);
                    return TODO_OK;
                    
                } else {
                    LogController::error("Error al actualizar el usuario ".$usuario->getID(),LOG_SERVER);
                    return ERROR;
                    
                }
            } else {
                return ERROR;   // NO PUDE BORRAR LAS AREAS
            }
            
             
        }
        else {
            LogController::warn("Se intentó ingresar un usuario que no existe: ".$usuario->getID(),LOG_SERVER);
            return USUARIO_INEXISTENTE;

        }
   }
    
    public function eliminarUsuario(){
        require_once 'src/private/Config.php';
        require_once 'src/models/Usuario.model.php';
        
        $usuarioABorrar = new Usuario($_POST['username']);
       
        if($usuarioABorrar->existe())
        {
            if($usuarioABorrar->getID() != $_SESSION['id'])
            {
                if($usuarioABorrar->eliminar()){
                    LogController::info("Se elimino el usuario ".$usuario->getID(),LOG_SERVER);
                    return USUARIO_ELIMINADO;
                }
                else {
                    LogController::error("Error al eliminar el usuario ".$usuario->getID(),LOG_SERVER);
                    return ERROR;
                }
               
                
            } else  {
                LogController::warn("El usuario ".$usuario->getID()." intento eliminarse a si mismo el muy pelotudo");
                return USUARIO_ERROR_BORRAR_A_SI_MISMO;
            }
        } else {
            LogController::warn("Se intento eliminar un usuario inexistente ".$usuario->getID(),LOG_SERVER);
            return USUARIO_INEXISTENTE;
        }
    }
    
    public function desbloquearUsuario(){
        require_once 'src/private/Config.php';
        require_once 'src/models/Usuario.model.php';
        
      
        $usuarioADesbloquear = new Usuario($_POST['id']);
       
        if($usuarioADesbloquear->existe())
        {
            if($usuarioADesbloquear->desbloquear())
            {
                LogController::info("Se desbloqueo el usuario ".$usuario->getID(),LOG_SERVER);
                return TODO_OK;
                
            } else {
                LogController::error("Error al desbloquear el usuario ".$usuario->getID(),LOG_SERVER);
                return ERROR;
            }
        } else {
            LogController::warn("Se intento desbloquear un usuario inexistente ".$usuario->getID(),LOG_SERVER);
            return USUARIO_INEXISTENTE;
        }
    }
    
    public function listaUsuarios(){
        
       require_once "src/models/Consulta.model.php";
       
       $listaDeUsuarios = Consulta::listaUsuarios();
       
       if(isset($listaDeUsuarios)){
           foreach ($listaDeUsuarios as $row => $user)
           {
               echo '<tr>';
               echo '<td>'.$user["ID"].'</td>';
               echo '<td>'.$user["NOMBRE"].'</td>';
               echo '<td>'.$user["APELLIDO"].'</td>';
               echo '<td>'.$user["SEXO"].'</td>';
               echo '<td>'.$user["PERFIL"].'</td>';
               echo '<td>'.Usuario::getEstadoLabel($user["ESTADO"]).'</td>';
               echo '<td>';
               echo '<form method="POST" action="index?s='.SECCION_ADMINISTRACION.'&m='.MENU_OPCIONES.'&o='.SUBMENU_USUARIOS.'">';
               echo '<button type="submit" name="editar" value="true" title="Editar" class="btn btn-minier btn-info">';
               echo '<i class="ace-icon fas fa-pencil-alt smaller-120"></i>';
               echo '</button> ';
               echo '<button type="submit" name="eliminar" value="true" title="Eliminar" class="btn btn-minier btn-danger">';
               echo '<i class="ace-icon fas fa-trash-alt smaller-120"></i>';
               echo '</button> ';
               echo '<button type="submit" name="desbloquear" value="true" title="Desbloquear" class="btn btn-minier btn-warning">';
               echo '<i class="ace-icon fas fa-unlock smaller-120"></i>';
               echo '</button>';
               echo '<input type="hidden" name="id" value="'.$user["ID"].'">';
               echo '</form>';
               echo '</td>';
               
               echo '</tr>';
           }
           
       } else {
           LogController::warn("No hay usuarios para mostrar");
           
       }
       
       
    }
    
    
    public function iniciarSesion(){
        
        $usuario = new Usuario(strtolower($_POST['username']));
        $usuario->cargar();
        
        session_start();
        $_SESSION['id'] = $usuario->getID();
        $_SESSION['validacion'] = true;
        $_SESSION['permisos'] = $usuario->getPermisosAplicados();
        
        $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['SKey'] = uniqid(mt_rand(), true);
        $_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['LastActivity'] = $_SERVER['REQUEST_TIME'];
       
    }
    

    public function cerrarSesion(){
        session_unset();
        session_destroy();
        unset($_SESSION);

    }
    
    public function verificarSesion(){
        session_start();
        if(!isset($_SESSION["validacion"]))
        {
            return false;
            exit();
        } else {
            session_start();
            return true;
            exit();
        }
    }
}
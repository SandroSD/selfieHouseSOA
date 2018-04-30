<?php
class PerfilController {
    
    public function cargarPerfil(){
        
        if(isset($_POST['id']))
        {
            require_once "src/controllers/Form.controller.php";
            require_once "src/models/Consulta.model.php";
            require_once "src/models/Perfil.model.php";
            
            $perfil = new Perfil($_POST['id']);
            
            if($perfil->cargar()){
                
                return $perfil;
                // Todo OK
                
            } else {
                LogController::warn("Se intento cargar un perfil inexistente: ".$perfil->getID(),LOG_SERVER);
                // Perfil no existente
            }
        } else {
            LogController::warn("Se intento cargar un perfil inexistente: ".$perfil->getID(),LOG_SERVER);
                // 403
        }
    }
    
    
    public function nuevoPerfil(){
        require_once 'src/private/Config.php';
        require_once 'src/models/Perfil.model.php';
        
        $perfil = new Perfil($_POST['id']);
        
        if(!$perfil->existe()) 
        {
            $perfil->setNombre($_POST['nombre']);
            
            # Puede que genere un perfil y no le asigne permisos, para el caso lo controlo
            
            if(isset($_POST['permisos']))
            {
                $perfil->setPermisos($_POST['permisos']);
            
            } else {
                
            }
            
            if($perfil->save(false)){
                LogController::info("Se ingreso el perfil ".$perfil->getID(),LOG_SERVER);
                return TODO_OK;

            } else {
                LogController::error("Hubo un error al ingresar el perfil ".$perfil->getID(),LOG_SERVER);
                return ERROR;
            }
        }
        else {
            LogController::warn("Se intento ingresar un perfil existente: ".$perfil->getID(),LOG_SERVER);
            return PERFIL_EXISTENTE;

        }
    }
    
    public function editarPerfil(){
        require_once 'src/private/Config.php';
        require_once 'src/models/Perfil.model.php';
         
        $perfil = new Perfil($_POST['id']);
        
        
        if($perfil->cargar())
        {
            $perfil->setNombre($_POST['nombre']);
            $perfil->setPermisos($_POST['permisos']);
            
            # Puede que modifique un usuario y no le asigne areas, para el caso lo controlo
                        
            if($perfil->save(true)){
                LogController::info("Se actualizo el perfil ".$perfil->getID(),LOG_SERVER);
                return TODO_OK;

            } else {
                LogController::error("Error al actualizar el perfil ".$perfil->getID(),LOG_SERVER);
                return ERROR;

            }
        }
        else {
            LogController::warn("Se intento actualizar un perfil inexistente: ".$perfil->getID(),LOG_SERVER);
            return PERFIL_INEXISTENTE;

        }
        
    }
    
    public function eliminarPerfil(){
        require_once 'src/private/Config.php';
        require_once 'src/models/Perfil.model.php';
        
        $perfilABorrar = new Perfil($_POST['id']);
        if($perfilABorrar->eliminar()){
            LogController::info("Se elimino el perfil ".$perfilABorrar->getID(),LOG_SERVER);
            return PERFIL_ELIMINADO;
        } else {
            
            LogController::error("Error al eliminar el perfil ".$perfilABorrar->getID(),LOG_SERVER);
            return ERROR;
        }
    }
    
    public function listaPerfiles(){
        
        require_once "src/models/Consulta.model.php";
        
        $listaDePerfiles = Consulta::listaPerfiles();
        
        if($listaDePerfiles) {
            foreach ($listaDePerfiles as $row => $perfil)
            {
                echo '<tr>';
                echo '<td>'.$perfil["ID"].'</td>';
                echo '<td>'.$perfil["NOMBRE"].'</td>';
                echo '<td>';
                echo '<form method="POST" action="index?s='.SECCION_ADMINISTRACION.'&m='.MENU_OPCIONES.'&o='.SUBMENU_PERFILES.'">';
                echo '<button type="submit" name="editar" value="true" title="Editar" class="btn btn-minier btn-info">';
                echo '<i class="ace-icon fas fa-pencil-alt smaller-120"></i>';
                echo '</button> ';
                echo '<button type="submit" name="eliminar" value="true" title="Eliminar" class="btn btn-minier btn-danger">';
                echo '<i class="ace-icon fas fa-trash-alt smaller-120"></i>';
                echo '</button> ';
                echo '<input type="hidden" name="id" value="'.$perfil["ID"].'">';
                echo '</form>';
                echo '</td>';
                
                echo '</tr>';
            }
        }        
   
        else {
            LogController::warn("PerfilController()::listaPerfiles() - No hay perfiles para mostrar",LOG_SERVER);
        }
    }
    
    
    
}
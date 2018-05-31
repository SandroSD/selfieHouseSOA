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
    
 
}
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
    
    
      
    
}
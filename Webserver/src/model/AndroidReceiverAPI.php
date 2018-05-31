<?php 
class AndroidReceiverAPI{


    public function API(){
        
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        switch ($metodo) {
            case 'GET'://consulta
                if(isset($_POST['accion']) && isset($_POST['latitud'])&& isset($_POST['longitud'])){
                    switch ($accion){
                        case OBTENER_UBICACION:
                            break;
                        default:
                            // error 400 - solicitud incorrecta
                            break;
                    }
                    
                } else {
                    // error 400 - solicitud incorrecta
                    
                }
                break;
            case 'POST'://inserta
                
                if(isset($_POST['accion']) && isset($_POST['disparador'])){
                    
                    $accion = $_POST['accion'];
                    $disparador = $_POST['disparador'];
                    
                    
                    switch ($accion){
                        
                        case NUEVA_FOTO:
                            /* el script deberia guardar la foto con un nombre
                             por algun elemento del post se deberia tener el nombre de ese archivo 
                             (el directorio deberia ser el mismo siempre).
                             desde aqui se deberia actualizar la base de datos con la solicitud de acceso y 
                             generar una notificacion para que sea leida desde el servicio de android
                             
                             */
                            break;
                        case SELFIEHOUSE_ACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/selfieon");
                            // habria que controlar si se hizo la accion.
                           
                            $comentario = "Se activó la alarma. Disparador: ".Conexion::disparadorLabel($disparador);
                            LogController::info($comentario,"LOG_SERVER");
                                                        
                            break;
                        case SELFIEHOUSE_DESACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/selfieoff");
                            $comentario = "Se activó el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
                            LogController::info($comentario,"LOG_SERVER");
                            
                            
                            break;                           
                        case DEBUG_ACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/debugon");
                            $comentario = "Se activó el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
                            LogController::info($comentario,"LOG_SERVER");
                            
                            break;
                        case DEBUG_DESACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/debugoff");
                            $comentario = "Se desactivó el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
                            LogController::info($comentario,"LOG_SERVER");
                            
                            break;
                            
                            break; 
                        case BUZZER_ACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/buzzon");
                            
                            if(Conexion::cambiarEstado(ID_BUZZER,ACTIVADO)){
                                $comentario = "Se encendió el buzzer. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,"LOG_SERVER");
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al encender el ventilador";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,"LOG_SERVER");
                            }
                            
                            break;
                            
                            break;
                        case BUZZER_DESACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/buzzoff");
                            
                            if(Conexion::cambiarEstado(ID_BUZZER,DESACTIVADO)){
                                $comentario = "Se apagó el buzzer. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,"LOG_SERVER");
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al apagar el buzzer";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,"LOG_SERVER");
                            }
                            
                            break;
                            
                            break;
                        case VENTILADOR_ACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/fanon");
                            
                            if(Conexion::cambiarEstado(ID_VENTILADOR,ACTIVADO)){
                                $comentario = "Se encendió el ventilador. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,"LOG_SERVER");
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al encender el ventilador";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,"LOG_SERVER");
                            }
                            
                            break;
                        case VENTILADOR_DESACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/fanoff");
                            
                            if(Conexion::cambiarEstado(ID_VENTILADOR,DESACTIVADO)){
                                $comentario = "Se apagó el ventilador. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,"LOG_SERVER");
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al apagar el ventilador";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,"LOG_SERVER");
                            }
                            
                            break;
                        case PUERTA_TRABADA:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/lock");
                            
                            if(Conexion::cambiarEstado(ID_TRABA,ACTIVADO)){
                                $comentario = "Se trabó la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,"LOG_SERVER");
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al trabar la puerta";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,"LOG_SERVER");
                            }
                            
                            break;
                        case PUERTA_DESTRABADA:
                            $html = file_get_contents("http://".IP_ARDUINO."/unlock");
                            
                            if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
                                $comentario = "Se destrabó la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,"LOG_SERVER");
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al destrabar la puerta";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,"LOG_SERVER");
                            }
                            
                            break;
                        default:
                            break;
                    }
                    
                } else {
                    
                    // error 400 -> solicitud incorrecta
                }
               
                break;
            case 'PUT'://actualiza
                echo 'PUT';
                break;
            case 'DELETE'://elimina
                echo 'DELETE';
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }





}
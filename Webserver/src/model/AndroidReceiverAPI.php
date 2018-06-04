<?php 
class AndroidReceiverAPI{

    public function API(){
        
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        switch ($metodo) {
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
                            LogController::info($comentario,LOG_SERVER);
                                                        
                            break;
                        case SELFIEHOUSE_DESACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/selfieoff");
                            $comentario = "Se activó el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
                            LogController::info($comentario,LOG_SERVER);
                            
                            
                            break;                           
                        case DEBUG_ACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/debugon");
                            $comentario = "Se activó el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
                            LogController::info($comentario,LOG_SERVER);
                            
                            break;
                        case DEBUG_DESACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/debugoff");
                            $comentario = "Se desactivó el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
                            LogController::info($comentario,LOG_SERVER);
                            
                            break;
                            
                            break; 
                        case BUZZER_ACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/buzzon");
                            
                            if(Conexion::cambiarEstado(ID_BUZZER,ACTIVADO)){
                                $comentario = "Se encendió el buzzer. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,LOG_SERVER);
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al encender el ventilador";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,LOG_SERVER);
                            }
                            
                            break;
                            
                            break;
                        case BUZZER_DESACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/buzzoff");
                            
                            if(Conexion::cambiarEstado(ID_BUZZER,DESACTIVADO)){
                                $comentario = "Se apagó el buzzer. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,LOG_SERVER);
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al apagar el buzzer";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,LOG_SERVER);
                            }
                            
                            break;
                            
                            break;
                        case VENTILADOR_ACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/fanon");
                            
                            if(Conexion::cambiarEstado(ID_VENTILADOR,ACTIVADO)){
                                $comentario = "Se encendió el ventilador. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,LOG_SERVER);
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al encender el ventilador";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,LOG_SERVER);
                            }
                            
                            break;
                        case VENTILADOR_DESACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/fanoff");
                            
                            if(Conexion::cambiarEstado(ID_VENTILADOR,DESACTIVADO)){
                                $comentario = "Se apagó el ventilador. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,LOG_SERVER);
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al apagar el ventilador";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,LOG_SERVER);
                            }
                            
                            break;
                        case PUERTA_TRABADA:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/lock");
                            
                            if(Conexion::cambiarEstado(ID_TRABA,ACTIVADO)){
                                $comentario = "Se trabó la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,LOG_SERVER);
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al trabar la puerta";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,LOG_SERVER);
                            }
                            
                            break;
                        case PUERTA_DESTRABADA:
                            $html = file_get_contents("http://".IP_ARDUINO."/unlock");
                            
                            if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
                                $comentario = "Se destrabó la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,LOG_SERVER);
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al destrabar la puerta";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,LOG_SERVER);
                            }
                            
                            break;
                        default:
							break;
                    }
                    
                } 
				# Con esto controlo el login a la casa, puede ser acceso de puerta o acceso al administrador
				else if (isset($_POST['codigo_acceso']) && isset($_POST['tipo_acceso'])){
					$nro = $_POST['codigo_acceso'];
					$tipoAcceso = $_POST['tipo_acceso'];
					
					LogController::info("AndroidReceiverAPI:: Solicitud recibida: POST -  desde la IP: ".$_SERVER['REMOTE_ADDR'],LOG_SERVER);
					
					# Verifica que el codigo recibido tenga el tipo de acceso solicitado
					if(verificarCodigoAcceso($nro,$tipoAcceso)){
						
						# Envia el comando a Arduino para destrabar la puerta (esto hace titilar el led verde).
						$html = file_get_contents("http://".IP_ARDUINO."/unlock");
						
						# Cambia el estado de la traba en la base de datos
						if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
                                $disparador = DISPARADOR_MANUAL;
								$comentario = "Se destrabó la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,LOG_SERVER);
								return "Autorizado";
                                
                            } else {
                                # Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al destrabar la puerta";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,LOG_SERVER);
								return "Error";
                            }
					} 
					
					
					else {
						# Esta es la respuesta que recibirá la APP de Android de esta API.
						return "No Autorizado";
					}
				}	
				# Esto sera accedido por un thread de la aplicacion Android para chequear si hay solicitudes nuevas de acceso
				else if (isset($_POST['pull_solicitudes'])){
				
					$solicitudes = Conexion::getSolicitudesDeAcceso();
					# Hay que ver como conviene mostrarlo, si con echo o return
					if($solicitudes){
						LogController::info("AndroidReceiverAPI:: Se informaron  ".count($solicitudes['ID'])." solicitudes de acceso pendientes a la IP: ".$_SERVER['REMOTE_ADDR'],LOG_DB);
						echo json_encode($solicitudes);
					} else {
						LogController::warn("AndroidReceiverAPI:: No hay solicitudes",LOG_SERVER);
						echo "No hay solicitudes";
				}	
						
				# Esto sera accedido por un thread de la aplicacion Android para chequear si hay solicitudes nuevas de acceso		
				} else if (isset($_POST['pull_notificaciones'])){
					$notificaciones = Conexion::getNotificacionesPendientes();
						
					# Hay que ver como conviene mostrarlo, si con echo o return
					if($notificaciones){
						LogController::info("AndroidReceiverAPI:: Se informaron  ".count($notificaciones['ID'])." notificaciones pendientes a la IP: ".$_SERVER['REMOTE_ADDR'],LOG_DB);
						echo json_encode($notificaciones);
					} else {
						LogController::warn("AndroidReceiverAPI:: No hay notificaciones",LOG_SERVER);
						echo "No hay notificaciones";
					}
				} else if (isset($_POST['pull_ubicacion'])){
					
					$ubicacion = Conexion::getUbicacion();
						
					# Hay que ver como conviene mostrarlo, si con echo o return
					if($ubicacion){
						LogController::info("AndroidReceiverAPI:: Se informo ubicacion del sistema embebido a la IP: ".$_SERVER['REMOTE_ADDR'],LOG_SERVER);
						echo json_encode($ubicacion);
					} else {
						LogController::warn("AndroidReceiverAPI:: Error al informar la ubicacion del embebido",LOG_SERVER);
						echo "No está definida la posicion del embebido";
					}
				
				} else if (isset($_POST['push_ubicacion']) && isset($_POST['latitud']) && isset($_POST['longitud'])){
					
					$latitud = $_POST['latitud'];
					$longitud = $_POST['longitud'];
						
					if(Conexion::setUbicacion($latitud,$longitud)){
						LogController::info("AndroidReceiverAPI:: Se definio la ubicacion del sistema embebido desde la IP: ".$_SERVER['REMOTE_ADDR'],LOG_SERVER);
						return "OK";
					} else {
						LogController::error("AndroidReceiverAPI:: Error al setear la ubicacion del embebido",LOG_SERVER);
						return "Error";
					}		
					
					# Hay que ver como conviene mostrarlo, si con echo o return
					if($ubicacion){
						LogController::info("AndroidReceiverAPI:: Se informo la latitud y longitud del sistema embebido a la IP: ".$_SERVER['REMOTE_ADDR'],LOG_SERVER);
						echo json_encode($ubicacion);
					} else {
						LogController::info("AndroidReceiverAPI:: Error al informar la posicion del embebido",LOG_SERVER);
						echo "No está definida la posicion del embebido";
					}
				}
				else {
                    // error 400 -> solicitud incorrecta
                }
               
                break;

            default: //metodo NO soportado
                LogController::warn("AndroidReceiverAPI:: Se recibio un metodo no aceptado desde la IP: ".$_SERVER['REMOTE_ADDR'],LOG_SERVER);
				echo 'Metodo no aceptado';
                break;
        }
    }





}
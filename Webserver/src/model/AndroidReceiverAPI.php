<?php 
class AndroidReceiverAPI{

    public function API(){
        ini_set('default_socket_timeout', 120);		// 120 segundos
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        switch ($metodo) {
            case 'GET':
				
				if (isset($_GET['pull_solicitudes'])){
					
					$solicitudes = Conexion::getSolicitudesDeAcceso();
					# Hay que ver como conviene mostrarlo, si con echo o return
					if($solicitudes){
						//LogController::info("AndroidReceiverAPI:: Se informaron solicitudes de acceso pendientes a la IP: ".$_SERVER['REMOTE_ADDR'],LOG_DB);
						echo json_encode($solicitudes);
					} else {
						//LogController::warn("AndroidReceiverAPI:: No hay solicitudes",LOG_SERVER);
						echo "No hay solicitudes";
					}	
				} else if (isset($_GET['pull_ubicacion'])){
					
					$ubicacion = Conexion::getUbicacion();
					
					# Hay que ver como conviene mostrarlo, si con echo o return
					if($ubicacion){
						//LogController::info("AndroidReceiverAPI:: Se informo ubicacion del sistema embebido a la IP: ".$_SERVER['REMOTE_ADDR'],LOG_SERVER);
						echo json_encode($ubicacion);
					} else {
						//LogController::warn("AndroidReceiverAPI:: Error al informar la ubicacion del embebido",LOG_SERVER);
						echo "No esta definida la posicion del embebido";
					}
				
				} else if (isset($_GET['pull_estados'])){
					
					$estados = Conexion::getEstadosComponentes();
					
					
					if($estados){
						//LogController::info("AndroidReceiverAPI:: Se informo ubicacion del sistema embebido a la IP: ".$_SERVER['REMOTE_ADDR'],LOG_SERVER);
						echo json_encode($estados);
					} else {
						//LogController::warn("AndroidReceiverAPI:: Error al informar la ubicacion del embebido",LOG_SERVER);
						echo "Error";
					}
				
				}
				
				
				else if (isset($_GET['pull_notificaciones'])){
					$notificaciones = Conexion::getNotificacionesPendientes();
						
					# Hay que ver como conviene mostrarlo, si con echo o return
					if($notificaciones){
						//LogController::info("AndroidReceiverAPI:: Se informaron  ".count($notificaciones['ID'])." notificaciones pendientes a la IP: ".$_SERVER['REMOTE_ADDR'],LOG_DB);
						echo json_encode($notificaciones);
					} else {
						//LogController::warn("AndroidReceiverAPI:: No hay notificaciones",LOG_SERVER);
						echo "No hay notificaciones";
					}
				}   
				else if(isset($_GET['accion']) && isset($_GET['disparador'])){
                    
                    $accion = $_GET['accion'];
                    $disparador = $_GET['disparador'];
                    
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
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								$comentario = "Se activo la alarma selfieHouse. Disparador: ".Conexion::disparadorLabel($disparador);
								echo "OK";
							} else {
								echo "Error";
								
							}
							
                        case SELFIEHOUSE_DESACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/selfieoff");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								$comentario = "Se desactivo la alarma selfieHouse. Disparador: ".Conexion::disparadorLabel($disparador);
								echo "OK";
							} else {
								echo "Error";
								
							}
							                            
                            break;                           
                        case DEBUG_ACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/debugon");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								$comentario = "Se activo el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
								echo "OK";
							} else {
								echo "Error";
								
							}
							break;
                        case DEBUG_DESACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/debugoff");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								$comentario = "Se desactivo el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
								echo "OK";
							} else {
								echo "Error";
								
							}
							break;
							
                        case BUZZER_ACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/buzzon");
                            $rta = json_decode($html);
                            if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_BUZZER,ACTIVADO)){
									$comentario = "Se encendió el buzzer. Disparador: ".Conexion::disparadorLabel($disparador);
								//    LogController::info($comentario,LOG_SERVER);
									echo "OK";
									
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
								//    LogController::error($comentarioError,LOG_SERVER);
									echo "Error";
								}
							else {
								echo "Error";
								
							}
                            
                            break;
                        case BUZZER_DESACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/buzzoff");
                            $rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_BUZZER,DESACTIVADO)){
									$comentario = "Se apagó el buzzer. Disparador: ".Conexion::disparadorLabel($disparador);
								   // LogController::info($comentario,LOG_SERVER);
									echo "OK";
									
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al apagar el buzzer";
									Conexion::nuevaNotificacion($comentarioError);
								 //   LogController::error($comentarioError,LOG_SERVER);
									echo "Error";
								}
							else {
								echo "Error";
								
							}
                            break;
                        case VENTILADOR_ACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/fanon");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_VENTILADOR,ACTIVADO)){
                                $comentario = "Se encendió el ventilador. Disparador: ".Conexion::disparadorLabel($disparador);
                              //  LogController::info($comentario,LOG_SERVER);
                                echo "OK";
                                
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
								 //   LogController::error($comentarioError,LOG_SERVER);
									echo "Error";
								}
								
							} else {
								echo "Error";
								
							}
							
							
                            
                            
                            break;
                        case VENTILADOR_DESACTIVADO:
                            
                            $html = file_get_contents("http://".IP_ARDUINO."/fanoff");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_VENTILADOR,DESACTIVADO)){
                                $comentario = "Se apago el ventilador. Disparador: ".Conexion::disparadorLabel($disparador);
                              //  LogController::info($comentario,LOG_SERVER);
                                echo "OK";
                                
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
								 //   LogController::error($comentarioError,LOG_SERVER);
									echo "Error";
								}
								
							} else {
								echo "Error";
								
							}
						                           
                            break;
                        case PUERTA_TRABADA:
                            $html = file_get_contents("http://".IP_ARDUINO."/lock");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_TRABA,ACTIVADO)){
                                $comentario = "Se trabó la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                              //  LogController::info($comentario,LOG_SERVER);
                                echo "OK";
                                
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
								 //   LogController::error($comentarioError,LOG_SERVER);
									echo "Error";
								}
								
							} else {
								echo "Error";
								
							}
						                           
                            break;
							
                        case PUERTA_DESTRABADA:
                            $html = file_get_contents("http://".IP_ARDUINO."/unlock");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
                                $comentario = "Se destrabó la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                              //  LogController::info($comentario,LOG_SERVER);
                                echo "OK";
                                
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
								 //   LogController::error($comentarioError,LOG_SERVER);
									echo "Error";
								}
								
							} else {
								echo "Error";
								
							}
						                           
                            break;
							
                        default:
                            echo "Error";
                            break;
                    }
                    
                } 
				break;
				
				case 'POST':
				
				# Con esto controlo el login a la casa, puede ser acceso de puerta o acceso al administrador
				if (isset($_POST['codigo_acceso']) && isset($_POST['tipo_acceso'])){
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
								$comentario = "Se destrabo la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                LogController::info($comentario,LOG_SERVER);
                                echo "Autorizado";
                                
                            } else {
                                # Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al destrabar la puerta";
                                Conexion::nuevaNotificacion($comentarioError);
                                LogController::error($comentarioError,LOG_SERVER);
                                echo "Error";
                            }
					} 
					
					
					else {
						# Esta es la respuesta que recibirÃ¡ la APP de Android de esta API.
					    echo "No Autorizado";
					}
				}	
				# Esto sera accedido por un thread de la aplicacion Android para chequear si hay solicitudes nuevas de acceso
				else if (isset($_POST['push_ubicacion']) && isset($_POST['latitud']) && isset($_POST['longitud'])){
					
					$latitud = $_POST['latitud'];
					$longitud = $_POST['longitud'];
						
					if(Conexion::setUbicacion($latitud,$longitud)){
						LogController::info("AndroidReceiverAPI:: Se definio la ubicacion del sistema embebido desde la IP: ".$_SERVER['REMOTE_ADDR'],LOG_SERVER);
						echo "OK";
					} else {
						LogController::error("AndroidReceiverAPI:: Error al setear la ubicacion del embebido",LOG_SERVER);
						echo "Error";
					}		
					
				}
				/* Se ejecuta ante la solicitud de un nuevo codigo con tipo de acceso.
				    Se recibe un true en nuevo_codigo y el tipo de acceso solicitado (222 ó 777)
				    Genera un codigo random de 6 cifras.
				    Si sale todo OK devuelve ese codigo.
				*/
				else if (isset($_POST['nuevo_codigo']) && isset($_POST['tipo_codigo'])){
				    
				    $tipo = $_POST['tipo_codigo'];
				    
				    // Genera un codigo aleatorio de 6 cifras
				    do {
				        
				        $codigo = Conexion::number_pad(rand(0,999999),6);
				    
				    } while(!Conexion::verificarCodigoExistente($codigo));
				    
				    if(Conexion::insertarCodigoAcceso($codigo,$tipo)){
				        echo $codigo;
				    } else {
				        echo "Error";
				    }
				    
				}
				
				
				else {
				    echo "Error";
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
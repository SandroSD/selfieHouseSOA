<?php 
class AndroidReceiverAPI{

    public function API(){
        ini_set('default_socket_timeout', 120);		// 120 segundos de timeout
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        switch ($metodo) {
            case 'GET':
				if (isset($_GET['pull_solicitudes'])){
					
					$solicitudes = Conexion::getSolicitudesDeAcceso();
					
					if($solicitudes){
						echo json_encode($solicitudes);
					} else {
						echo "No hay solicitudes";
					}	
					
				} else if (isset($_GET['pull_ubicacion'])){
					
					$ubicacion = Conexion::getUbicacion();
					
					if($ubicacion){
						echo json_encode($ubicacion);
					} else {
						echo "No hay datos";
					}
				
				} else if (isset($_GET['pull_estados'])){
					
					$estados = Conexion::getEstadosComponentes();
					
					if($estados){
						echo json_encode($estados);
					} else {
						echo "Error";
					}
				
				} else if (isset($_GET['pull_cantidades_pendientes'])){
					
					$cantidades = Conexion::getCantidadesPendientes();
					
					if($cantidades){
						echo json_encode($cantidades);
					} else {
						echo "Error";
					}
				
				} else if (isset($_GET['pull_notificaciones'])){
					
					$notificaciones = Conexion::getNotificacionesPendientes();
					
					# Hay que ver como conviene mostrarlo, si con echo o return
					if($notificaciones){
						echo json_encode($notificaciones);
					} else {
						echo "No hay notificaciones";
					}
				} else if (isset($_GET['codigo_acceso']) && isset($_GET['tipo_acceso'])){
					$nro = $_GET['codigo_acceso'];
					$tipoAcceso = $_GET['tipo_acceso'];
					Conexion::agregarAlLog(1,"AndroidReceiverAPI:: Solicitud recibida: GET -  desde la IP: ".$_SERVER['REMOTE_ADDR']);
					
					# Verifica que el codigo recibido tenga el tipo de acceso solicitado
						
					if(Conexion::verificarCodigoAcceso($nro,$tipoAcceso)){
						
						# Envia el comando a Arduino para destrabar la puerta (esto hace titilar el led verde).
						$html = file_get_contents("http://".IP_ARDUINO."/unlock");
						$rta = json_decode($html);
						
						if($rta->{'respuesta'} == "OK"){
						
						# Cambia el estado de la traba en la base de datos
							if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
									$disparador = DISPARADOR_MANUAL;
									$comentario = "Se destrabo la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
									echo "Autorizado";
									
							} else {
								# Logueo el error y mando notificacion
								$comentarioError = "Hubo un error al destrabar la puerta";
								Conexion::nuevaNotificacion($comentarioError);
								Conexion::agregarAlLog(2,$comentarioError);
								echo "Error";
							}
						}
					} 
					
					
					else {
						# Esta es la respuesta que recibirÃ¡ la APP de Android de esta API.
					    echo "No Autorizado";
					}
				}	
				
				else if(isset($_GET['accion']) && isset($_GET['disparador'])){
                    
                    $accion = $_GET['accion'];
                    $disparador = $_GET['disparador'];
                    
                    switch ($accion){
                        
                        case SELFIEHOUSE_ACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/selfieon");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_SELFIEHOUSE,ACTIVADO)){
									$comentario = "Se activo la alarma selfieHouse. Disparador: ".Conexion::disparadorLabel($disparador);
									
									Conexion::agregarAlLog(1,$comentario);
									echo "OK";
									
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al activar el mdoo DEBUG";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
									echo "Error";
								}
								
								
							} else {
								echo "Error";
								
							}
							
                        case SELFIEHOUSE_DESACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/selfieoff");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								
								if(Conexion::cambiarEstado(ID_SELFIEHOUSE,DESACTIVADO)){
									$comentario = "Se desactivo la alarma selfieHouse. Disparador: ".Conexion::disparadorLabel($disparador);
									Conexion::agregarAlLog(1,$comentario);
									echo "OK";
									
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al activar el mdoo DEBUG";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
									echo "Error";
								}
							
							} else {
								echo "Error";
								
							}
							                            
                            break;                           
                        case DEBUG_ACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/debugon");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_DEBUG,ACTIVADO)){
									$comentario = "Se activo el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
									Conexion::agregarAlLog(1,$comentario);
									echo "OK";
									
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al activar el mdoo DEBUG";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
									echo "Error";
								}
							} else {
								echo "Error";
								
							}
							break;
							
                        case DEBUG_DESACTIVADO:
                            $html = file_get_contents("http://".IP_ARDUINO."/debugoff");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_DEBUG,DESACTIVADO)){
									$comentario = "Se desactivo el modo DEBUG. Disparador: ".Conexion::disparadorLabel($disparador);
									Conexion::agregarAlLog(1,$comentario);
									echo "OK";
									
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al desactivar el modo DEBUG";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
									echo "Error";
								}
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
									 Conexion::agregarAlLog(1,$comentario);
									echo "OK";
									
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
									echo "Error";
								}
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
									Conexion::agregarAlLog(1,$comentario);
									echo "OK";
									
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al apagar el buzzer";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
									echo "Error";
								}
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
                                Conexion::agregarAlLog(1,$comentario);
								echo "OK";
                                
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
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
								Conexion::agregarAlLog(1,$comentario);
                                echo "OK";
                                
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
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
                                Conexion::agregarAlLog(1,$comentario);
                                echo "OK";
                                
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
									echo "Error";
								}
								
							} else {
								Conexion::agregarAlLog(2,"No se pudo trabar la puerta");
								echo "Error";
								
							}
						    break;
							
                        case PUERTA_DESTRABADA:
                            $html = file_get_contents("http://".IP_ARDUINO."/unlock");
							$rta = json_decode($html);
							if($rta->{'respuesta'} == "OK"){
								if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
                                $comentario = "Se destrabó la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                Conexion::agregarAlLog(1,$comentario);
                                echo "OK";
                                
								} else {
									// Logueo el error y mando notificacion
									$comentarioError = "Hubo un error al encender el ventilador";
									Conexion::nuevaNotificacion($comentarioError);
									Conexion::agregarAlLog(2,$comentarioError);
									echo "Error";
								}
								
							} else {
								Conexion::agregarAlLog(2,"No se pudo destrabar la puerta");
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
				
				# Esto sera accedido por un thread de la aplicacion Android para chequear si hay solicitudes nuevas de acceso
				if (isset($_POST['push_ubicacion']) && isset($_POST['latitud']) && isset($_POST['longitud'])){
					
					$latitud = $_POST['latitud'];
					$longitud = $_POST['longitud'];
						
					if(Conexion::setUbicacion($latitud,$longitud)){
						Conexion::agregarAlLog(1,"AndroidReceiverAPI:: Se definio la ubicacion del sistema embebido desde la IP: ".$_SERVER['REMOTE_ADDR']);
						echo "OK";
					} else {
						
						Conexion::agregarAlLog(2,"AndroidReceiverAPI:: Error al setear la ubicacion del embebido");
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
				        Conexion::agregarAlLog(2,"AndroidReceiverAPI:: Error al insertar un codigo de acceso nuevo");
						echo "Error";
				    }
				    
				}
				
				
				else {
				    Conexion::agregarAlLog(3,"AndroidReceiverAPI:: Se recibio una solicitud incorrecta desde la IP: ".$_SERVER['REMOTE_ADDR']);
					echo "Error";
                    // error 400 -> solicitud incorrecta
                }
               
                break;

            default: //metodo NO soportado
                Conexion::agregarAlLog(3,"AndroidReceiverAPI:: Se recibio un metodo no aceptado desde la IP: ".$_SERVER['REMOTE_ADDR']);
				echo 'Metodo no aceptado';
                break;
        }
    }


}
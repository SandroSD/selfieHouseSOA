<?php
/*
 * Aca se maneja la recepcion, almacenamiento y envio de notificaciones de solicitud de acceso
 *
 * Este controlador debe recibir los parametros GET: "accion" y "disparador"
 *
 * "accion"     -> corresponde a la acción a ejecutar sobre el actuador.
 * "disparador" -> es el evento que lo ocasionó.
 *
 * */

class ArduinoReceiverAPI{
    
    public function API(){
        
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        switch($metodo){
            
            case 'GET':
                
                if(isset($_GET['accion']) && isset($_GET['disparador']))
                {
                    
                    $accion = $_GET['accion'];
                    $disparador = $_GET['disparador'];
                    
                    switch($accion){
                        
                        case BUZZER_ACTIVADO:
                            // Actualizo estado en DB
                            
                            if(Conexion::cambiarEstado(ID_BUZZER,ACTIVADO))
                            {
                                $comentario = "Se activo la alarma buzzer. Disparador: ".Conexion::disparadorLabel($disparador);
                                
                                // Enviar notificacion
                                Conexion::nuevaNotificacion($comentario);
								Conexion::agregarAlLog(1,$comentario);
                                echo "OK";
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al activar la alarma buzzer";
                                Conexion::nuevaNotificacion($comentarioError);
                                Conexion::agregarAlLog(2,$comentarioError);
								echo "Error";
                            }
                            
                            break;
                        case BUZZER_DESACTIVADO:
                            
                            // Actualizo estado en DB
                            if(Conexion::cambiarEstado(ID_BUZZER,DESACTIVADO)){
                                $comentario = "Se desactivo la alarma buzzer. Disparador: ".Conexion::disparadorLabel($disparador);
                                
                                // Enviar notificacion
                                Conexion::nuevaNotificacion($comentario);
                                Conexion::agregarAlLog(1,$comentario);
								echo "OK";
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al desactivar la alarma buzzer";
                                Conexion::nuevaNotificacion($comentarioError);
                                Conexion::agregarAlLog(2,$comentarioError);
								echo "Error";
                            }
                            
                            break;
                        case VENTILADOR_ACTIVADO:
                            
                            // Actualizo estado en DB
                            if(Conexion::cambiarEstado(ID_VENTILADOR,ACTIVADO)){
                                $comentario = "Se activo el ventilador. Disparador: ".Conexion::disparadorLabel($disparador);
                                
                                // Enviar notificacion
                                Conexion::nuevaNotificacion($comentario);
                                Conexion::agregarAlLog(1,$comentario);
								echo "OK";
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al activar el ventilador";
                                Conexion::nuevaNotificacion($comentarioError);
                                Conexion::agregarAlLog(2,$comentarioError);
								echo "Error";
                            }
                            
                            break;
                        case VENTILADOR_DESACTIVADO:
                            
                            // Actualizo estado en DB
                            if(Conexion::cambiarEstado(ID_VENTILADOR,DESACTIVADO)){
                                $comentario = "Se desactivo el ventilador. Disparador: ".Conexion::disparadorLabel($disparador);
                                
                                // Enviar notificacion
                                Conexion::nuevaNotificacion($comentario);
                                Conexion::agregarAlLog(1,$comentario);
								echo "OK";
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al desactivar el ventilador";
                                Conexion::nuevaNotificacion($comentarioError);
                                Conexion::agregarAlLog(2,$comentarioError);
								echo "Error";
                            }
                            
                            break;
                        case PUERTA_TRABADA:
                            
                            // Actualizo estado en DB
                            if(Conexion::cambiarEstado(ID_TRABA,ACTIVADO)){
                                $comentario = "Se trabo la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                
                                // Enviar notificacion
                                Conexion::nuevaNotificacion($comentario);
                                Conexion::agregarAlLog(1,$comentario);
								echo "OK";
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al trabar la puerta";
                                Conexion::nuevaNotificacion($comentarioError);
								Conexion::agregarAlLog(2,$comentarioError);
								echo "Error";
                            }
                            
                            break;
                        case PUERTA_DESTRABADA:
                            
                            // Actualizo estado en DB
                            if(Conexion::cambiarEstado(ID_TRABA,DESACTIVADO)){
                                $comentario = "Se destrabo la puerta. Disparador: ".Conexion::disparadorLabel($disparador);
                                
                                // Enviar notificacion
                                Conexion::nuevaNotificacion($comentario);
								Conexion::agregarAlLog(1,$comentario);
							   echo "OK";
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al destrabar la puerta";
                                Conexion::nuevaNotificacion($comentarioError);
								Conexion::agregarAlLog(2,$comentarioError);
							   echo "Error";
                            }
                            
                            break;
                        case REINICIO_ESTADOS:
                            
                            // Actualizo todos los estados de la DB
                           
                            if(Conexion::reiniciarEstados()){
                                $comentario = "Se acaba de iniciar el dispositivo. Se sincronizan los estados de componentes.";
                                
                                // Enviar notificacion
                                Conexion::nuevaNotificacion($comentario);
								Conexion::agregarAlLog(1,$comentario);
							   echo "OK";
                                
                            } else {
                                // Logueo el error y mando notificacion
                                $comentarioError = "Hubo un error al actualizar estados de los componentes";
                                Conexion::nuevaNotificacion($comentarioError);
								Conexion::agregarAlLog(2,$comentarioError);
								echo "Error";
                              
                            }
                            
                            break;
                            
                        default:
                            // Accion no valida
							echo "Error";
                            break;
                    }
                    
                } else {
                    // 400 - solicitud incorrecta
					echo "Error";
                }
                
                break;
            default: //metodo NO soportado
                // error 404
                echo 'Error';
                break;
        }
        
        
    }
}
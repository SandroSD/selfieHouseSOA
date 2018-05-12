<?php
/*
 * Aca se manejan las instrucciones al webserver de Arduino
 *
 * */

class CasaControl {
    
    
    public function trabarPuerta(){
        $html = file_get_contents("http://".DIRECCION_ARDUINO."/lock");
        if($html = "OK"){
            // Actualizar la base de datos         
            // Enviar notificacion que si se pudo completar la accion
        } else {
            
            // Enviar notificacion que no se pudo completar la accion
        }
        
    }
    
    public function destrabarPuerta(){
        $html = file_get_contents("http://".DIRECCION_ARDUINO."/unlock");
        if($html = "OK"){
            // Actualizar la base de datos
            // Enviar notificacion que si se pudo completar la accion
        } else {
            
            // Enviar notificacion que no se pudo completar la accion
        }
    }
    
    public function encenderVentilador(){
        $html = file_get_contents("http://".DIRECCION_ARDUINO."/fanon");
        if($html = "OK"){
            // Actualizar la base de datos
            // Enviar notificacion que si se pudo completar la accion
        } else {
            
            // Enviar notificacion que no se pudo completar la accion
        }
    }
    
    public function apagarVentilador(){
        $html = file_get_contents("http://".DIRECCION_ARDUINO."/fanoff");
        if($html = "OK"){
            // Actualizar la base de datos
            // Enviar notificacion que si se pudo completar la accion
        } else {
            
            // Enviar notificacion que no se pudo completar la accion
        }
    }
    
    public function sonarBuzzer(){
        $html = file_get_contents("http://".DIRECCION_ARDUINO."/buzzon");
        if($html = "OK"){
            // Actualizar la base de datos
            // Enviar notificacion que si se pudo completar la accion
        } else {
            
            // Enviar notificacion que no se pudo completar la accion
        }
    }
    
    public function apagarBuzzer(){
        $html = file_get_contents("http://".DIRECCION_ARDUINO."/buzzoff");
        if($html = "OK"){
            // Actualizar la base de datos
            // Enviar notificacion que si se pudo completar la accion
        } else {
            
            // Enviar notificacion que no se pudo completar la accion
        }
    }
}
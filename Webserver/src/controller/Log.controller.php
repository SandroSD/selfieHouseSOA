<?php
class LogController {
    
     
    
    public function info($texto,$tipo){
        $log = new Log($tipo);
        $log->write("[ INFO]-[",date('Y-m-d H:i:s')."]-".$texto);
    }
    
    public function debug($texto,$tipo){
        $log = new Log($tipo);
        $log->write("[DEBUG]-[",date('Y-m-d H:i:s')."]-".$texto);
    }
    
    public function error($texto,$tipo){
        $log = new Log($tipo);
        $log->write("[ERROR]-[",date('Y-m-d H:i:s')."]-".$texto);
    }
    
    public function warn($texto,$tipo){
        $log = new Log($tipo);
        $log->write("[ WARN]-[",date('Y-m-d H:i:s')."]-".$texto);
    }
    
    public function critical($texto,$tipo){
        $log = new Log($tipo);
        $log->write("[ CRIT]-[",date('Y-m-d H:i:s')."]-".$texto);
    }
    
    public function mostrarLog($tipo){
        $log = new Log($tipo);
       
        return $log->mostrar();
        
    }
    
    public function truncarLog($tipo){
        $log = new Log($tipo);
        if($log->truncar()){
            return TODO_OK;
        } else {
            return ERROR;
        }
    }
}
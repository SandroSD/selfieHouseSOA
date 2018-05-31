<?php
class Log {
    
    protected $dir, $filename,$fr;
    protected $inicialPath;
    protected $completeDir;
    
    public function __construct($tipo){
       // $this->inicialPath = PATH_LOG1;
        $this->dir = PATH_LOG;
        $this->filename = $tipo;
        $this->completeDir = $this->dir.$this->filename.".log";
               
    }
    
    public function setCompleteDir($val){
        $this->completeDir = $val;
    }
    
    public function write($severidad, $texto){
       
        if(!is_writable($this->completeDir)){ 
            die ("Error al abrir el log");
        }
        $handle = fopen($this->completeDir,'a+');
        fwrite($handle, $severidad."".$texto."\r\n");
        fclose($handle);
    }
    
    public function read(){
        
    }
    
    public function truncar(){
        if(unlink($this->completeDir)){
            $file = fopen($this->completeDir, 'w');
            fclose($file);
            return true;
        }
        return false;
    }
    
    public function interpretar(){
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = $this->completeDir;
        }
        // set default log file for Linux and other systems
        else {
            $log_file_default = 'C:/log/file/selfieHouse.log';
        }
        // define log file from lfile method or use previously set default
        $lfile =$this->completeDir ? $this->completeDir : $log_file_default;
        // open log file for writing only and place file pointer at the end of the file
        // (if the file does not exist, try to create it)
        $this->fr = fopen($lfile, 'r') or exit("No se pudo abrir el archivo $lfile!");
        
        $pila = new Pila(LOG_SIZE);
        
        while($linea = fgets($this->fr)){
           
            // ESTO SE HACE SON SUBSTR
            $lineaLog = new LineaLog();
            //echo substr($linea, 0, 8);
            $lineaLog->setSeveridad(substr($linea, 0, 7));
            $lineaLog->setFecha(substr($linea, 9, 10));
            $lineaLog->setHora(substr($linea, 11, 8));
            //$lineaLog->setUsuario(substr($linea, 30, 8));
            $lineaLog->setDetalle(substr($linea, 30, strpos($linea, PHP_EOL, 33)), true);
            //$lineaLog->show();
            $pila->push($lineaLog);
            
        }
        
        return $pila;
    }
    
    public function mostrar(){
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = $this->completeDir;
        }
        // set default log file for Linux and other systems
        else {
            $log_file_default = 'C:/log/file/selfieHouse.log';
        }
        // define log file from lfile method or use previously set default
        $lfile =$this->completeDir ? $this->completeDir : $log_file_default;
        // open log file for writing only and place file pointer at the end of the file
        // (if the file does not exist, try to create it)
        $this->fr = fopen($lfile, 'r') or exit("No se pudo abrir el archivo $lfile!");
        
        while($linea = fgets($this->fr)){
            echo $linea;
        }
        
        return true;
    }
}
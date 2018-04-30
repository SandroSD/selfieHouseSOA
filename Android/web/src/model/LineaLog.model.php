<?php
class LineaLog{
    
    private $fecha, $hora, $severidad, $detalle,$usuario;
    
    public function __construct(){
        $this->fecha = "";
        $this->hora = "";
        $this->severidad = "";
        $this->detalle = "";
        $this->usuario = "";
    }
    
    
    public function setFecha($val){
        $this->fecha = $val;
    }
    
    public function setHora($val){
        $this->hora = $val;
    }
    public function setUsuario($val){
        $this->usuario = $val;
    }
    public function setSeveridad($val){
        $this->severidad = $val;
    }
    
    public function setDetalle($val){
        $this->detalle = $val;
    }
    
    public function getFecha(){
        return $this->fecha;
    }
    
    public function getHora(){
        return $this->hora;
    }
    public function getUsuario(){
        return $this->usuario;
    }
    public function getSeveridad(){
        return $this->severidad;
    }
    
    public function getDetalle(){
        return $this->detalle;
    }
    
    
    public function getSeveridadLabel(){
        if($this->severidad == "[ WARN]"){
            return"<span class='label label-sm label-warning'>WARN</span>";
        }else if ($this->severidad == "[ INFO]"){
            return "<span class='label label-sm label-info'>INFO</span>";
        }
        else if ($this->severidad == "[ERROR]"){
            return"<span class='label label-sm label-danger'>ERROR</span>";
        }
    }
        
    public function show(){
        echo "Fecha: ".$this->fecha."<br>";
        echo "Hora: ".$this->hora."<br>";
        echo "Usuario: ".$this->usuario."<br>";
        echo "Severidad: ".$this->getSeveridadLabel()."<br>";
        echo "Detalle: ".$this->detalle."<br><br>";
    }
    
}
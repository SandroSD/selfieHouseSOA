<?php
class Pila {
	
	public $size;
	public $elements;
	public $top;
	
	public function Pila($size=10){
		$this->size=$size;
		$this->top=0;
		$this->elements=array();
	}
	
	
	public function pop(){
		$element=null;
		if($this->top>0)
		{ 
			$element=$this->elements[$this->top]; 
			$this->top--;
		}
		else { 
			echo "PILA VACIA";
		}
		return $element;
	}
	
	
	public function push($element){
		if($this->top<$this->size){
			$this->top++;
			$this->elements[$this->top]=$element;
		}else {
			echo "PILA LLENA";
		}
	}
	
	public function isVacia(){
		if($this->top == 0){
			return true;
		} else {
			return false;
		}
	}
}
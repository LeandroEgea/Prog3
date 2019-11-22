<?php
  class Log     
  {
    public $ip;
    public $ruta;
    public $metodo;
    public $fecha;
    
    public function __construct($ip, $ruta, $metodo, $timestamp)
    {
        $this->ip = $ip;
        $this->ruta = $ruta;
        $this->metodo = $metodo;
        $this->fecha = $timestamp;
    }
  }
 ?>
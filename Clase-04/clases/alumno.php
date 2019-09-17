<?php
    class Alumno
    {
        public $nombre;
        public $apellido;
        public $legajo;

        public function __construct($nombre, $apellido, $legajo, $imagen)
        {
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->legajo = $legajo;
            $this->imagen = $imagen;
        }

        public function saludar()
        {
             echo 'Hola '.$this->nombre.' '.$this->apellido.PHP_EOL;
        }
    }
?>
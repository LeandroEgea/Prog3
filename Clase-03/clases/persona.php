<?php
    class Persona
    {
        public $nombre;
        public $apellido;

        public function __construct($nombre, $apellido)
        {
            $this->nombre = $nombre;
            $this->apellido = $apellido;
        }

        public function saludar()
        {
             echo 'Hola '.$this->nombre.' '.$this->apellido.PHP_EOL;
        }
    }
?>
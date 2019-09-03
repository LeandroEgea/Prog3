<?php
    class Alumno extends Persona
    {
        public $legajo;
        public $cuatrimestre;

        public function __construct($nombre, $dni, $legajo, $cuatrimestre)
        {
            parent::__construct($nombre, $dni);
            $this->legajo = $legajo;
            $this->cuatrimestre = $cuatrimestre;
        }
    }
?>
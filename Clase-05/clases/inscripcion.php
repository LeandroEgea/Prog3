<?php
class Inscripcion
{
    public $nombreAlumno;
    public $apellidoAlumno;
    public $emailAlumno;
    public $nombreMateria;
    public $codigoMateria;

    public function __construct($nombreAlumno, $apellidoAlumno, $emailAlumno, $nombreMateria, $codigoMateria)
    {
        $this->nombreAlumno = $nombreAlumno;
        $this->apellidoAlumno = $apellidoAlumno;
        $this->emailAlumno = $emailAlumno;
        $this->nombreMateria = $nombreMateria;
        $this->codigoMateria = $codigoMateria;
    }
}
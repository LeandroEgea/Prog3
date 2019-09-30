<?php
class Materia
{
    public $nombre;
    public $codigo;
    public $cupo;
    public $aula;

    public function __construct($nombre, $codigo, $cupo, $aula)
    {
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->cupo = $cupo;
        $this->aula = $aula;
    }
}

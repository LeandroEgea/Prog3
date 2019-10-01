<?php
class Usuario
{
    public $legajo;
    public $email;
    public $nombre;
    public $clave;
    public $fotoUno;
    public $fotoDos;

    public function __construct($legajo, $email, $nombre, $clave, $fotoUno, $fotoDos)
    {
        $this->legajo = $legajo;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->clave = $clave;
        $this->fotoUno = $fotoUno;
        $this->fotoDos = $fotoDos;
    }
}

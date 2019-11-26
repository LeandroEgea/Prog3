<?php
namespace App\Models\ORM;

class Log extends \Illuminate\Database\Eloquent\Model
{
    protected $id;
    protected $fecha;
    protected $ip;
    protected $ruta;
    protected $metodo;
    protected $usuario;
}

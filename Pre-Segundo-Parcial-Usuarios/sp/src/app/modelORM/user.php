<?php
namespace App\Models\ORM;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $email;
    protected $legajo;
    protected $clave;
    protected $foto_uno;
    protected $fotos_dos;
}

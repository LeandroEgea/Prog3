<?php  
namespace App\Models\ORM;
 
 use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class User extends \Illuminate\Database\Eloquent\Model {  
    protected $email;
    protected $legajo;
    protected $clave;
    protected $fotoUno;
    protected $fotosDos;
}

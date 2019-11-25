<?php  
namespace App\Models\ORM;
 
 use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class Log extends \Illuminate\Database\Eloquent\Model {  
  protected $ruta;
  protected $metodo;
  protected $fecha;
  protected $ip;
  protected $usuario;


}

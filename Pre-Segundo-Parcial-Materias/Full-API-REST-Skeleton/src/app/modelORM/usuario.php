<?php  
namespace App\Models\ORM;
 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Usuario extends \Illuminate\Database\Eloquent\Model {  
    protected $id;
    protected $legajo;
    protected $email;
    protected $clave;
    protected $tipo_id;
}

<?php  
namespace App\Models\ORM;
 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Materia extends \Illuminate\Database\Eloquent\Model {  
    protected $id;
    protected $nombre;
    protected $cuatrimestre;
    protected $cupos;
}

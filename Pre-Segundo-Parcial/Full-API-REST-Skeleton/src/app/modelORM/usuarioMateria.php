<?php  
namespace App\Models\ORM;
 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsuarioMateria extends \Illuminate\Database\Eloquent\Model {  
    protected $usuario_id;
    protected $matertia_id;
    protected $table = 'usuarios_materias';
}

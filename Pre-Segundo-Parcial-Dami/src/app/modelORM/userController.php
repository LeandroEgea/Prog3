<?php
namespace App\Models\ORM;
use App\Models\ORM\User;
use App\Models\IApiControler;

include_once __DIR__ . '/user.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class userController implements IApiControler 
{
 	public function Beinvenida($request, $response, $args) {
      $response->getBody()->write("GET => Bienvenido!!! ,a UTN FRA SlimFramework");
    
    return $response;
    }
    
     public function TraerTodos($request, $response, $args) {
       	//return cd::all()->toJson();
        $todosLosCds=cd::all();
        $newResponse = $response->withJson($todosLosCds, 200);  
        return $newResponse;
    }
    public function TraerUno($request, $response, $args) {
     	//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
    	return $newResponse;
    }
   
      public function CargarUno($request, $response, $args) {
        $arrayDeParametros = $request->getParsedBody();
        $arrayArchivos = $request->getUploadedFiles();
        $user = new User;
        $user->email = $arrayDeParametros["email"];
        $user->legajo = $arrayDeParametros["legajo"];
        $user->clave = $arrayDeParametros["clave"];
        $tmpName = $arrayArchivos["fotoUno"]->file;
        $extension = $arrayArchivos["fotoUno"]->getClientFilename();
        $extension = explode(".", $extension);
        $filenameUno = "./images/users/" . $user->email . "1." . $extension[1]; 
        //rename($tmpName,$filenameUno);
        $arrayArchivos["fotoUno"]->moveTo($filenameUno);

        $user->fotoUno =  $filenameUno;

        $tmpName = $arrayArchivos["fotoDos"]->file;
        $extension = $arrayArchivos["fotoDos"]->getClientFilename();
        $extension = explode(".", $extension);
        $filenameDos = "./images/users/" . $user->email . "2." . $extension[1]; 
        $arrayArchivos["fotoDos"]->moveTo($filenameDos);

        $user->fotoDos =  $filenameDos;
        //$user->save();

        //$userAMostrar = User::find($user->id);
        unset($userAMostrar["clave"]);
        $newResponse = $response->withJson($user, 200);
        return $newResponse; 
    }
      public function BorrarUno($request, $response, $args) {
  		//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
      	return $newResponse;
    }
     
     public function ModificarUno($request, $response, $args) {
     	//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
		return 	$newResponse;
    }


  
}
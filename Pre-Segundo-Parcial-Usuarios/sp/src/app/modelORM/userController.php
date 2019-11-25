<?php
namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
use App\Models\ORM\Egreso;
use App\Models\ORM\Ingreso;
use App\Models\ORM\User;

include_once __DIR__ . '../../modelAPI/AutentificadorJWT.php';
include_once __DIR__ . '/ingreso.php';
include_once __DIR__ . '/egreso.php';
include_once __DIR__ . '/user.php';

class UserController
{
    public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();
        $archivos = $request->getUploadedFiles();

        if (false) { //TODO: Guard
            return $response->withJson('Introduzca todos los datos', 400);
        }

        $buscarPorLegajo = User::where('legajo', '=', $body["legajo"])->get()->toArray();
        $buscarPorEmail = User::where('email', '=', $body["email"])->get()->toArray();
        if ($buscarPorLegajo != null || $buscarPorEmail != null) {
            return $response->withJson('El legajo o email ya existe', 500);
        }

        $user = new User;
        $user->email = $body["email"];
        $user->legajo = $body["legajo"];
        $user->clave = $body["clave"];

        $tmpName = $archivos["fotoUno"]->file;
        $extension = $archivos["fotoUno"]->getClientFilename();
        $extension = explode(".", $extension);
        $filenameUno = "./images/users/" . $user->email . "1." . $extension[1];
        $archivos["fotoUno"]->moveTo($filenameUno);
        $user->foto_uno = $filenameUno;

        $tmpName = $archivos["fotoDos"]->file;
        $extension = $archivos["fotoDos"]->getClientFilename();
        $extension = explode(".", $extension);
        $filenameDos = "./images/users/" . $user->email . "2." . $extension[1];
        $archivos["fotoDos"]->moveTo($filenameDos);
        $user->foto_dos = $filenameDos;

        $user->save();

        $userAMostrar = User::where('legajo', '=', $user->legajo)->get()[0];
        unset($userAMostrar["clave"], $userAMostrar["created_at"], $userAMostrar["updated_at"]);
        return $response->withJson($userAMostrar, 200);
    }

    public function Login($request, $response, $args)
    {
        if (false) { //TODO: Guard
            return $response->withJson('Introduzca todos los datos', 400);
        }

        $body = $request->getParsedBody();
        $usuarios = User::where('users.legajo', '=', $body["legajo"])
            ->select('users.legajo', 'users.email', 'users.clave')
            ->get()
            ->toArray();

        if (count($usuarios) == 1 && $usuarios[0]["clave"] == $body["clave"]
            && strtolower($usuarios[0]["email"]) == strtolower($body["email"])) {
            $usuario = $usuarios[0];
            unset($usuario["clave"]);
            $token = AutentificadorJWT::CrearToken($usuario);
            return $response->withJson($token, 200);
        } else {
            return $response->withJson("No se pudo iniciar sesion, vuelva a intertarlo", 200);
        }
    }

    public function FicharIngreso($request, $response, $args)
    {
        $legajo = $request->getAttribute('legajo');

        $buscarPorLegajo = Ingreso::where('legajo', '=', $legajo)->get()->toArray();
        if ($buscarPorLegajo != null) {
            return $response->withJson('El legajo ya esta ingresado', 500);
        }

        $ingreso = new Ingreso;
        $ingreso->legajo = $legajo;
        $ingreso->save();

        $ingresoAMostrar = Ingreso::find($ingreso->id);
        unset($ingresoAMostrar["updated_at"]);
        return $response->withJson($ingresoAMostrar, 200);
    }

    public function FicharEgreso($request, $response, $args)
    {
        $legajo = $request->getAttribute('legajo');

        $buscarIngreso = Ingreso::where('legajo', '=', $legajo)->get()->toArray();
        if ($buscarIngreso == null) {
            return $response->withJson('El usuario no esta ingresado', 500);
        }

        $egreso = new Egreso;
        $egreso->legajo = $legajo;
        $egreso->save();

        $egresoAMostrar = Egreso::find($egreso->id);
        unset($egresoAMostrar["updated_at"]);
        return $response->withJson($egresoAMostrar, 200);
    }

    public function ObtenerIngresos($request, $response, $args)
    {
        $legajo = $request->getAttribute('legajo');
        if ($legajo < 100) {
            return $this->ObtenerIngresosAdmin($response);
        }
        return $this->ObtenerIngresosUsuario($legajo, $response);
    }

    public function ObtenerIngresosAdmin($response)
    {
        return $response->withJson("Ingresos admin", 200);
    }

    public function ObtenerIngresosUsuario($legajo, $response)
    {
        return $response->withJson("Ingresos usuario " . $legajo, 200);
    }
}

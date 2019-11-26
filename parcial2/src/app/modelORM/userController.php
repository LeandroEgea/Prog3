<?php
namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
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

        if (!array_key_exists("email", $body) ||
            !array_key_exists("legajo", $body) ||
            !array_key_exists("clave", $body) ||
            !array_key_exists("fotoUno", $archivos) ||
            !array_key_exists("fotoDos", $archivos)) {
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
        $body = $request->getParsedBody();

        if (!array_key_exists("email", $body) ||
            !array_key_exists("legajo", $body) ||
            !array_key_exists("clave", $body)) {
            return $response->withJson('Introduzca todos los datos', 400);
        }

        $usuarios = User::where('users.legajo', '=', $body["legajo"])
            ->select('users.legajo', 'users.email', 'users.clave')
            ->get()
            ->toArray();

        if (count($usuarios) != 1) {
            return $response->withJson("No se encontro el usuario", 401);
        }
        if (strtolower($usuarios[0]["email"]) != strtolower($body["email"])) {
            return $response->withJson("Email invalido", 401);
        }
        if ($usuarios[0]["clave"] != $body["clave"]) {
            return $response->withJson("Clave invalida", 401);
        }

        $usuario = $usuarios[0];
        unset($usuario["clave"]);
        $token = AutentificadorJWT::CrearToken($usuario);
        return $response->withJson($token, 200);
    }

    public function FicharIngreso($request, $response, $args)
    {
        $legajo = $request->getAttribute('legajo');

        $buscarPorLegajo = Ingreso::where('legajo', '=', $legajo)->get()->toArray();
        if ($buscarPorLegajo != null) {
            foreach ($buscarPorLegajo as $ing) {
                if ($ing["status"] == "ingresado") {
                    return $response->withJson('El legajo ya esta ingresado', 500);
                }
            }
        }

        $ingreso = new Ingreso;
        $ingreso->legajo = $legajo;
        $ingreso->fecha = date('Y-m-d H:i:s', $request->getServerParam('REQUEST_TIME'));
        $ingreso->status = "ingresado";
        $ingreso->save();

        $ingresoAMostrar = Ingreso::find($ingreso->id);
        unset($ingresoAMostrar["updated_at"], $ingresoAMostrar["created_at"],
            $ingresoAMostrar["id"]);
        return $response->withJson($ingresoAMostrar, 200);
    }

    public function FicharEgreso($request, $response, $args)
    {
        $legajo = $request->getAttribute('legajo');

        $buscarIngreso = Ingreso::where('legajo', '=', $legajo)->get()->toArray();
        if ($buscarIngreso == null) {
            return $response->withJson('El usuario no esta ingresado', 500);
        } else {
            $isIngresado = false;
            foreach ($buscarIngreso as $ing) {
                if ($ing["status"] == "ingresado") {
                    $isIngresado = true;
                    Ingreso::where('legajo', '=', $ing["legajo"])
                        ->update(array('status' => "egresado"));
                }
            }
            if ($isIngresado === false) {
                return $response->withJson('El usuario no esta ingresado', 500);
            }
        }

        $egreso = new Egreso;
        $egreso->legajo = $legajo;
        $egreso->fecha = date('Y-m-d H:i:s', $request->getServerParam('REQUEST_TIME'));
        $egreso->save();

        $egresoAMostrar = Egreso::find($egreso->id);
        unset($egresoAMostrar["updated_at"], $egresoAMostrar["created_at"],
            $egresoAMostrar["id"]);
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
        return $response->withJson("Ingresos admin falta hacer. Esta hecho el de usuarios", 200);
    }

    public function ObtenerIngresosUsuario($legajo, $response)
    {
        $ingresos = Ingreso::where('legajo', '=', $legajo)->get()->toArray();
        return $response->withJson($ingresos, 200);
    }
}

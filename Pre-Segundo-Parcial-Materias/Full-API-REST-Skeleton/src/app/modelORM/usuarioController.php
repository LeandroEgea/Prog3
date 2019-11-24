<?php
namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
use App\Models\ORM\Usuario;
use App\Models\ORM\UsuarioMateria;

include_once __DIR__ . '/usuario.php';
include_once __DIR__ . '/usuarioMateria.php';
include_once __DIR__ . '../../modelAPI/AutentificadorJWT.php';

class UsuarioController
{
    public function Login($request, $response, $args)
    {
        $body = $request->getParsedBody();
        $usuarios = Usuario::where('usuarios.email', '=', $body["email"])
            ->join('tipos', 'usuarios.tipo_id', 'tipos.id')
            ->select('usuarios.id', 'usuarios.legajo', 'usuarios.email', 'usuarios.tipo_id', 'tipos.tipo', 'usuarios.clave')
            ->get()
            ->toArray();

        if (count($usuarios) == 1 && $usuarios[0]["clave"] == $body["clave"]) {
            $usuario = $usuarios[0];
            unset($usuario["clave"]);
            $token = AutentificadorJWT::CrearToken($usuario);
            $newResponse = $response->withJson($token, 200);
        } else {
            $newResponse = $response->withJson("No se pudo iniciar sesion, vuelva a intertarlo", 200);
        }
        return $newResponse;
    }

    public function TraerTodos($request, $response, $args)
    {
        $usuarios = Usuario::where('usuarios.id', '>', '0')
            ->join('tipos', 'usuarios.tipo_id', 'tipos.id')
            ->select('usuarios.legajo', 'usuarios.email', 'tipos.tipo')
            ->get();

        $newResponse = $response->withJson($usuarios, 200);
        return $newResponse;
    }

    public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();
        $usuario = new Usuario();
        $usuario->email = $body["email"];
        $usuario->clave = $body["clave"];
        $usuario->tipo_id = $body["tipo_id"];
        $usuario->legajo = rand(10000, 99999);
        $usuario->save();

        $usuario = Usuario::find($usuario->id);
        unset($usuario["clave"], $usuario["created_at"], $usuario["updated_at"]);

        return $response->withJson($usuario, 200);
    }

    public function ModificarUno($request, $response, $args)
    {
        $tipo = $request->getAttribute('tipo');
        $body = $request->getParsedBody();
        $usuarios = usuario::where('usuarios.legajo', '=', $args["legajo"])
            ->join('tipos', 'usuarios.tipo_id', 'tipos.id')
            ->select('usuarios.id', 'usuarios.legajo', 'tipos.tipo')
            ->get()
            ->toArray();

        if (count($usuarios) == 1 && ($usuarios[0]["legajo"] == $args["legajo"] || $tipo == "admin")) {
            $usuario = $usuarios[0];
            unset($usuario["created_at"], $usuario["updated_at"], $usuario["clave"]);
            switch ($tipo) {
                case "alumno":
                    return $this->ModificarAlumno($usuario, $body, $response);
                case "profesor":
                    return $this->ModificarProfesor($usuario, $body, $response);
                case "admin":
                    switch ($usuario["tipo"]) {
                        case "alumno":
                            return $this->ModificarAlumno($usuario, $body, $response);
                        case "profesor":
                        case "admin":
                            return $this->ModificarProfesor($usuario, $body, $response);
                    }
                default:
                    return $response->withJson("Tipo Usuario Invalido", 500);
            }
        } else {
            return $response->withJson("Los campos seleccionados no corresponden al usuario", 500);
        }
    }

    public function ModificarAlumno($alumno, $body, $response)
    {
        $alumno = Usuario::find($alumno["id"]);
        if (array_key_exists("email", $body)) {
            $alumno->email = $body["email"];
        }
        $alumno->save();
        unset($alumno["clave"], $alumno["created_at"], $alumno["updated_at"]);
        return $response->withJson($alumno, 200);
    }

    public function ListaAlumnos($profesor, $body, $response)
    {
        $profesor = Usuario::find($profesor["id"]);
        if (array_key_exists("email", $body)) {
            $profesor->email = $body["email"];
        }
        if (array_key_exists("materias", $body)) {
            $ids = explode(",", $body["materias"]);
            foreach ($ids as $id) {
                if (Materia::find($id) != null) {
                    $where = [['usuarios_materias.materia_id', '=', $id], ['usuarios_materias.usuario_id', '=', $profesor->id]];
                    if (UsuarioMateria::where($where)
                        ->select('usuarios_materias.usuario_id')
                        ->get()
                        ->toArray() == null) {
                        $usuarioMateria = new UsuarioMateria;
                        $usuarioMateria->materia_id = $id;
                        $usuarioMateria->usuario_id = $profesor->id;
                        $usuarioMateria->save();
                    }
                }
            }
        }
        $profesor->save();
        unset($profesor["clave"], $profesor["created_at"], $profesor["updated_at"]);
        return $response->withJson($profesor, 200);
    }
}

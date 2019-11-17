<?php
namespace App\Models\ORM;

use App\Models\ORM\Materia;

include_once __DIR__ . '/materia.php';

class MateriaController
{
    public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();
        $materia = new Materia();
        $materia->nombre = $body["nombre"];
        $materia->cuatrimestre = $body["cuatrimestre"];
        $materia->cupos = $body["cupos"];
        $materia->save();

        $materia = Materia::find($materia->id);
        unset($materia["created_at"], $materia["updated_at"]);

        return $response->withJson($materia, 200);
    }

    public function InscripcionAlumno($request, $response, $args)
    {
        $materia = Materia::find($args['idmateria']);
        $where = [['usuarios_materias.materia_id', '=', $args['idmateria']],
            ['usuarios_materias.usuario_id', '=', $request->getAttribute('id')]];
        if (
            $materia != null &&
            UsuarioMateria::where($where)
            ->select('usuarios_materias.usuario_id')
            ->get()
            ->toArray() == null
        ) {
            $usuarioMateria = new UsuarioMateria;
            $usuarioMateria->materia_id = $args["idmateria"];
            $usuarioMateria->usuario_id = $request->getAttribute('id');
            $usuarioMateria->save();
            unset($usuarioMateria["created_at"], $usuarioMateria["updated_at"], $usuarioMateria["id"]);
            $materia->cupos--;
            $materia->save();
            return $response->withJson($usuarioMateria, 200);
        } else {
            return $response->withJson("No se pudo inscribir", 500);
        }
    }

    public function TraerTodos($request, $response, $args)
    {
        $tipo = $request->getAttribute('tipo');
        $id = $request->getAttribute('id');
        switch ($tipo) {
            case "alumno":
            case "profesor":
                return $this->ObtenerMateriasDelUsuario($id, $response);
            case "admin":
                return $this->Obtener($response);
            default:
                return $response->withJson("Tipo Usuario Invalido", 500);
        }
    }

    public function ObtenerMateriasDelUsuario($id, $response)
    {
        $materias = UsuarioMateria::where('usuarios_materias.usuario_id', '=', $id)
            ->join('materias', 'usuarios_materias.materia_id', 'materias.id')
            ->select('usuarios_materias.materia_id', 'materias.nombre', 'materias.cuatrimestre', 'materias.cupos')
            ->get();
        return $response->withJson($materias, 200);
    }

    public function Obtener($response)
    {
        $materias = Materia::all(['id', 'nombre', 'cuatrimestre', 'cupos']);
        return $response->withJson($materias, 200);
    }

    public function ListaAlumnos($request, $response, $args)
    {
        $tipo = $request->getAttribute('tipo');
        $idUsuario = $request->getAttribute('id');
        $idMateria = $args['idmateria'];
        if (Materia::find($idMateria) == null) {
            return $response->withJson("Materia Not found", 404);
        }
        if ($tipo == "profesor") {
            $where = [['usuarios_materias.materia_id', '=', $idMateria],
                ['usuarios_materias.usuario_id', '=', $idUsuario]];
            if (
                UsuarioMateria::where($where)
                ->select('usuarios_materias.usuario_id')
                ->get()
                ->toArray() == null) {
                return $response->withJson("No sos profesor de esta materia", 401);
            }
        }
        $where = [['usuarios_materias.materia_id', '=', $idMateria],
            ['tipos.tipo', '=', "alumno"]];
        $alumnos = UsuarioMateria::where($where)
            ->join('usuarios', 'usuarios.id', 'usuarios_materias.usuario_id')
            ->join('tipos', 'tipos.id', 'usuarios.tipo_id')
            ->select('usuarios.id', 'usuarios.legajo', 'usuarios.email')
            ->get()
            ->toArray();
        return $response->withJson($alumnos, 200);
    }
}

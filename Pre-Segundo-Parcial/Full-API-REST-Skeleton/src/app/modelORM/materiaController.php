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
            unset($usuarioMateria["created_at"], $usuarioMateria["updated_at"]);
            $materia->cupos--;
            $materia->save();
            return $response->withJson($usuarioMateria, 200);
        }
        else{
            return $response->withJson("No se pudo inscribir", 500);
        }
    }
}

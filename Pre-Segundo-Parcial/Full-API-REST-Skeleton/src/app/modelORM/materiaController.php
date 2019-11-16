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
}

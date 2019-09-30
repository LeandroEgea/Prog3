<?php
class MateriaController
{
    public $materiasDao;

    public function __construct()
    {
        $this->materiasDao = new GenericDao('./materias.txt');
    }

    public function cargarMateria($nombre, $codigo, $cupo, $aula)
    {
        $materiaExistente = $this->materiasDao->getObjectByKeyCaseInsensitive("codigo", $codigo);
        if (is_null($materiaExistente)) {
            $materia = new Materia($nombre, $codigo, $cupo, $aula);
            $this->materiasDao->guardar($materia);
            echo 'Se cargo la materia ' . $materia->nombre;
        } else {
            echo 'Hubo un error al guardar';
        }
    }

}

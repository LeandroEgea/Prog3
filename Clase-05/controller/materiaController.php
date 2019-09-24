<?php
class MateriaController
{
    //TODO deberia ser estatico la clase y el atributo
    public $materiasDao;

    public function __construct()
    {
        $this->materiasDao = new GenericDao('./materias.txt');
    }

    //TODO Checkear que no este usado el codigo
    function cargarMateria($nombre, $codigo, $cupo, $aula) {                           
        $materia = new Materia($nombre, $codigo, $cupo, $aula);
        $rta = $this->materiasDao->guardar($materia);
        if ($rta === true) {
            echo 'Guardada';
        } else {
            echo 'Hubo un error al guardar';
        }
    }
}
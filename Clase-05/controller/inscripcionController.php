<?php
class InscripcionController
{
    //TODO deberia ser estatico la clase y el atributo
    public $inscripcionesDao;
    public $materiasDao;

    public function __construct()
    {
        $this->inscripcionesDao = new GenericDao('./inscripciones.txt');
        $this->materiasDao = new GenericDao('./materias.txt');
    }

    function inscribirAlumno($nombreAlumno, $apellidoAlumno, $emailAlumno, $nombreMateria, $codigoMateria) {
        $materiaObtenida = $this->materiasDao->obtenerPorId("codigo", $codigoMateria);
        if(!is_null($materiaObtenida) && $materiaObtenida->cupo > 0){
            $inscripcion = new Inscripcion($nombreAlumno, $apellidoAlumno, $emailAlumno, $nombreMateria, $codigoMateria);
            $rta = $this->inscripcionesDao->guardar($inscripcion);
            if ($rta === true) {
                $materiaObtenida->cupo--;
                $rta = $this->materiasDao->modificar("codigo",$codigoMateria, "cupo", $materiaObtenida->cupo);
                if($rta === true)
                {
                    echo 'Alumno inscripto';
                }
                else
                {
                    echo 'Hubo un error al restar el cupo de la materia';
                }
            } else {
                echo 'Hubo un error al inscribir el alumno';
            }
        }
        else
        {
            echo 'La materia no existe o no tiene cupos';
        }
    }
}
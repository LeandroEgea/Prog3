<?php
class InscripcionController
{
    public $inscripcionesDao;
    public $materiasDao;
    public $alumnosDao;

    public function __construct()
    {
        $this->inscripcionesDao = new GenericDao('./inscripciones.txt');
        $this->materiasDao = new GenericDao('./materias.txt');
        $this->alumnosDao = new GenericDao('./alumnos.txt');
    }

    public function inscribirAlumno($nombreAlumno, $apellidoAlumno, $emailAlumno, $nombreMateria, $codigoMateria)
    {
        //Valido que la materia exista
        $materiaObtenida = $this->materiasDao->getObjectByKeyCaseInsensitive("codigo", $codigoMateria);
        //Valido que el alumno exista
        $alumnoObtenido = $this->alumnosDao->getObjectByKeyCaseInsensitive("email", $emailAlumno);
        if (!is_null($materiaObtenida) && !is_null($materiaObtenida) && $materiaObtenida->cupo > 0) {
            $inscripcion = new Inscripcion($nombreAlumno, $apellidoAlumno, $emailAlumno, $nombreMateria, $codigoMateria);
            $rta = $this->inscripcionesDao->guardar($inscripcion);
            if ($rta === true) {
                //materia con cupo restado
                $cupoRestado = $materiaObtenida->cupo - 1;
                $materiaAux = new Materia($materiaObtenida->nombre, $materiaObtenida->codigo, (string) $cupoRestado, $materiaObtenida->aula);
                $rta = $this->materiasDao->modificar("codigo", $codigoMateria, $materiaAux);
                if ($rta === true) {
                    echo 'Se inscribio el alumno';
                } else {
                    echo 'Hubo un error al restar el cupo de la materia';
                }
            } else {
                echo 'Hubo un error al inscribir el alumno';
            }
        } else {
            echo 'Hubo un error al inscribir el alumno  ';
        }
    }
    public function mostrarInscripciones()
    {
        $rta = $this->inscripcionesDao->listar();
        if ($rta !== null) {
            echo $rta;
        } else {
            echo 'Hubo un error al mostrar la informacion';
        }
    }

    public function mostrarInscripcionesFiltro($GET)
    {
        $rta = "";
        if (array_key_exists("codigoMateria", $GET) && !array_key_exists("apellidoAlumno", $GET)) {
            $rta = "Alumnos filtrados por materia\n" . $this->inscripcionesDao->getObjectsByKeyCaseInsensitive("codigoMateria", $GET["codigoMateria"]);
        } elseif (array_key_exists("apellidoAlumno", $GET) && !array_key_exists("codigoMateria", $GET)) {
            $rta = "Alumnos filtrados por apellido\n" . $this->inscripcionesDao->getObjectsByKeyCaseInsensitive("apellidoAlumno", $GET["apellidoAlumno"]);
        } elseif (array_key_exists("apellidoAlumno", $GET) && array_key_exists("codigoMateria", $GET)) {
            $rta = "No se pueden filtrar los campos apellido y materia juntos";
        }
        echo $rta;
    }
}

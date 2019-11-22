<?php
class cdApi
{
    public $alumnoController;

    public function __construct()
    {
        $this->alumnoController = new AlumnoController();
    }

    public function traerTodos($request, $response, $args){ 
        $alumnos = $this->alumnoController->mostrarAlumnos();
        return $response->getbody()->write($alumnos);
    }
    
    public function traerUno($request, $response, $args){ 
        $apellido = $args['apellido'];
        $alumno = $this->alumnoController->consultarAlumno($apellido);
        return $response->getbody()->write(json_encode($alumno));
    }

    public function cargarUno($request, $response, $args){ 
        return $response->getbody()->write("Hello world POST");
    }

    public function modificarUno($request, $response, $args){ 
        return $response->getbody()->write("Hello world PUT");
    }

    public function borrarUno($request, $response, $args){ 
        return $response->getbody()->write("Hello world DELETE");
    }


}
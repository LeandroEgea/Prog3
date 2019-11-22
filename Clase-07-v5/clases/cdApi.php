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

    public function traerTodosExceptoUnCampo($request, $response, $args){
        $campo = $campo; 
        $alumnos = $this->alumnoController->mostrarAlumnosExceptoUnCampo($campo);
        return $response->getbody()->write($alumnos);
    }
    
    public function traerUno($request, $response, $args){ 
        $apellido = $args["apellido"];
        $alumno = $this->alumnoController->consultarAlumno($apellido);
        return $response->getbody()->write(json_encode($alumno));
    }

    public function cargarUno($request, $response, $args){ 
        $arrayDeParametros = $request->getParsedBody();
        $nombre = $arrayDeParametros['nombre'];
        $apellido = $arrayDeParametros['apellido'];
        $email = $arrayDeParametros['email'];
        $archivos = $request->getUploadedFiles();
        $foto = $archivos['foto'];
        
        $rv = $this->alumnoController->cargarAlumno($nombre, $apellido, $email, $foto);
        return $response->getbody()->write($rv);
    }

    public function modificarUno($request, $response, $args){ 
        $archivos = $request->getUploadedFiles();
        $parametros = $request->getParsedBody(); 
        $rv = $this->alumnoController->modificarAlumno($archivos, $parametros);
        return $response->getbody()->write($rv);
    }

    public function borrarUno($request, $response, $args){ 
        $arrayDeParametros = $request->getParsedBody();
        $email = $arrayDeParametros['email'];
        $rv = $this->alumnoController->borrarAlumno($email);
        return $response->getbody()->write($rv);
    }


}
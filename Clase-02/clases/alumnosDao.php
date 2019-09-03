<?php
    class AlumnosDao
    {
        public function __construct()
        {
            session_start();
            if(!isset($_SESSION["Alumnos"])){
                $_SESSION["Alumnos"] = array();
            }
        }
        
        function guardar($alumno){
            array_push($_SESSION["Alumnos"], $alumno);
        }

        function borrar($alumnoLegajo){
            foreach($_SESSION["Alumnos"] as $key => $alumno){
                if($alumno->legajo == $alumnoLegajo){
                    unset($_SESSION["Alumnos"][$key]);
                }
            }
        }

        function listar(){
            $arrayAux = json_encode($_SESSION["Alumnos"]);
            return $arrayAux;
        }

        function modificar($alumnoLegajo, $alumnoNombre){
            foreach($_SESSION["Alumnos"] as $key => $alumno){
                if($alumno->legajo == $alumnoLegajo){
                    $alumno->nombre = $alumnoNombre;
                }
            }
        }

    }

?>
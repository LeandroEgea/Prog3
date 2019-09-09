<?php
    class PersonasDao
    {
        public function __construct()
        {

        }
        
        function guardar($persona){
            $archivo = fopen("./texto.txt", "a");
            fwrite($archivo, PHP_EOL.$persona->nombre.' - '.$persona->apellido.' - '.$persona->legajo);
            fclose($archivo);
        }

        function listar(){
            $personas = array();
            $archivo = fopen("./texto.txt", "r");
            while(!feof($archivo)) {
                $persAux = explode(" - ", fgets($archivo));
                if(count($persAux) > 2) {
                    $persona = new Persona($persAux[0], $persAux[1], $persAux[2]);
                    array_push($personas, $persona);
                }
            }
            fclose($archivo);
            return json_encode($personas);
        }

        function borrar($alumnoLegajo){
            foreach($_SESSION["Alumnos"] as $key => $alumno){
                if($alumno->legajo == $alumnoLegajo){
                    unset($_SESSION["Alumnos"][$key]);
                }
            }
        }

    }

?>
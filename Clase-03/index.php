<?php
    INCLUDE './clases/persona.php';

    $request = ($_SERVER['REQUEST_METHOD']);

    switch($request){
        case "POST" : 
            if(isset($_POST["Nombre"]) && isset($_POST["Apellido"])) {
                $archivo = fopen("./texto.txt", "a");
                $rta = fwrite($archivo, PHP_EOL.$_POST["Nombre"].' - '.$_POST["Apellido"]);
                $rta2 = fclose($archivo);
            }
            break;

        case "GET" : 
            $archivo = fopen("./texto.txt", "r");
            while(!feof($archivo)) {
                $persAux = explode(" - ", fgets($archivo));
                $persona = new Persona($persAux[0], $persAux[1]);
                $persona->saludar();
            }
            $rta2 = fclose($archivo);
            break;
    }
?>
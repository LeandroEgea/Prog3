<?php
    INCLUDE './clases/persona.php';
    INCLUDE './clases/genericDao.php';

    $request = ($_SERVER['REQUEST_METHOD']);
    $dao = new GenericDao('../texto.txt');

    switch($request){
        case "POST" : 
            if(isset($_POST["Nombre"]) && isset($_POST["Apellido"]) && isset($_POST["Legajo"])) {
                $persona = new Persona($_POST["Nombre"], $_POST["Apellido"], $_POST["Legajo"]);
                $dao->guardar($persona);
            }
            break;

        case "GET" : 
            echo $dao->listar();
            break;
        
    }
?>
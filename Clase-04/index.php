<?php
    //TODO validar que sea una imagen(image/) que no supere los 2 mb

    /*$tmpName = $_FILES["imagen"]["tmp_name"];
    $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
    $rta = move_uploaded_file($tmpName, "./".$_FILES["imagen"]["name"]);*/

    INCLUDE './clases/alumno.php';
    INCLUDE './clases/genericDao.php';

    $request = ($_SERVER['REQUEST_METHOD']);
    $dao = new GenericDao('./texto.txt');

    switch($request){
        case "POST" : 
            if(isset($_POST["Nombre"]) && isset($_POST["Apellido"]) && isset($_POST["Legajo"]) 
                && isset($_FILES["Imagen"])) {
                $tmpName = $_FILES["Imagen"]["tmp_name"];
                $extension = pathinfo($_FILES["Imagen"]["name"], PATHINFO_EXTENSION);
                $filename = "./imagenes/".$_POST["Legajo"].".".$extension;
                $rta = move_uploaded_file($tmpName, $filename);
                if($rta == true) {
                    $alumno = new Alumno($_POST["Nombre"], $_POST["Apellido"], $_POST["Legajo"], $filename);
                    $dao->guardar($alumno);
                    echo 'Saved';
                }
                else {
                    echo 'Something went wrong';
                }  
            }
            break;

        case "GET" : 
            echo $dao->listar();
            break;

        case "DELETE" : 
            if(isset($_GET["Legajo"])){
                $dao->borrar("legajo", $_GET["Legajo"]);
            }
            break;
    }
?>
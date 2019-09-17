<?php
    //TODO validar que sea una imagen(image/) que no supere los 2 mb
    //TODO pasar todas las $_post a minuscula ("imagen") 

    /*$tmpName = $_FILES["imagen"]["tmp_name"];
    $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
    $rta = move_uploaded_file($tmpName, "./".$_FILES["imagen"]["name"]);*/

    INCLUDE './clases/alumno.php';
    INCLUDE './clases/genericDao.php';

    $request = ($_SERVER['REQUEST_METHOD']);
    $dao = new GenericDao('./texto.txt');

    switch($request){
        case "POST" : 
            if(isset($_POST["case"]) && $_POST["case"] == "alta" && isset($_POST["nombre"]) 
                && isset($_POST["apellido"]) && isset($_POST["legajo"]) && isset($_FILES["imagen"])) {
                $tmpName = $_FILES["imagen"]["tmp_name"];
                $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
                $filename = "./imagenes/".$_POST["legajo"].".".$extension;
                $rta = move_uploaded_file($tmpName, $filename);
                if($rta == true) {
                    $alumno = new Alumno($_POST["nombre"], $_POST["apellido"], $_POST["legajo"], $filename);
                    $dao->guardar($alumno);
                    echo 'Saved';
                }
                else {
                    echo 'Something went wrong';
                }  
            }
            //TODO modificacion para nombre y apellido
            else if (isset($_POST["case"]) && $_POST["case"] == "modificacion" && isset($_POST["legajo"]) 
                && isset($_FILES["imagen"])) {
                //TODO backupear
                $tmpName = $_FILES["imagen"]["tmp_name"];
                $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
                $filename = "./imagenes/".$_POST["legajo"].".".$extension;
                $rta = move_uploaded_file($tmpName, $filename);
                if($rta == true) {
                    $dao->modificar("legajo", $_POST["legajo"],"imagen", $filename);
                    echo 'Modified';
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
            //TODO borrar imagen
            if(isset($_GET["legajo"])){
                $dao->borrar("legajo", $_GET["legajo"]);
            }
            break;
    }
?>
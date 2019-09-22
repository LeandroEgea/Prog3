<?php
//TODO usar el rta en las llamadas al DAO

include './clases/alumno.php';
include './clases/genericDao.php';

$request = ($_SERVER['REQUEST_METHOD']);
$dao = new GenericDao('./texto.txt');

switch ($request) {
    case "POST":
        if (isset($_POST["case"])) {
            switch ($_POST["case"]) {
                case "alta":
                    if (isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["legajo"])
                        && isset($_FILES["imagen"]) && isImage($_FILES["imagen"])
                        && tamanoValidoEnMb(($_FILES["imagen"]), 2)) {
                        $tmpName = $_FILES["imagen"]["tmp_name"];
                        $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
                        $filename = "./imagenes/" . $_POST["legajo"] . "." . $extension;
                        //hacer mas elegante lo del rta
                        $rta = move_uploaded_file($tmpName, $filename);
                        if ($rta == true) {
                            $alumno = new Alumno($_POST["nombre"], $_POST["apellido"], $_POST["legajo"], $filename);
                            $dao->guardar($alumno);
                            echo 'Guardado';
                        } else {
                            echo 'Hubo un error';
                        }
                    } else {
                        echo "Hubo un error en los datos enviados";
                    }
                    break;
                //TODO modificacion para nombre y apellido
                case "modificacion":
                    if (isset($_POST["legajo"]) && isset($_FILES["imagen"]) && isImage($_FILES["imagen"])
                        && tamanoValidoEnMb(($_FILES["imagen"]), 2)) {
                        //TODO backupear
                        $tmpName = $_FILES["imagen"]["tmp_name"];
                        $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
                        $filename = "./imagenes/" . $_POST["legajo"] . "." . $extension;
                        //hacer mas elegante lo del rta
                        $rta = move_uploaded_file($tmpName, $filename);
                        if ($rta == true) {
                            $dao->modificar("legajo", $_POST["legajo"], "imagen", $filename);
                            echo 'Modificado';
                        } else {
                            echo 'Hubo un error';
                        }
                    } else {
                        echo "Hubo un error en los datos enviados";
                    }
                    break;
                case "borrar":
                    //TODO borrar imagen (usar obtener por id)
                    if (isset($_GET["legajo"])) {
                        $dao->borrar("legajo", $_GET["legajo"]);
                    }
                    break;
            }
        } else {
            echo 'Indique el case correctamente';
        }
        break;
    case "GET":
        echo $dao->listar();
        break;
}

function isImage($imagen): bool
{
    if (explode("/", $_FILES["imagen"]["type"])[0] == "image") {
        return true;
    } else {
        throw new Exception("No es un archivo de imagen");
    }
}

function tamanoValidoEnMb($archivo, $mb): bool
{
    if (($_FILES["imagen"]["size"]) < ($mb * 1024 * 1024)) {
        return true;
    } else {
        throw new Exception("Tamaño maximo $mb mb");
    }
}

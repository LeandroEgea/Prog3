<?php
include './clases/alumno.php';
include './clases/genericDao.php';

$request = ($_SERVER['REQUEST_METHOD']);
$dao = new GenericDao('./texto.txt');

try {
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
                            $rta = move_uploaded_file($tmpName, $filename);
                            if ($rta === true) {
                                $alumno = new Alumno($_POST["nombre"], $_POST["apellido"], $_POST["legajo"], $filename);
                                $rta = $dao->guardar($alumno);
                                if ($rta === true) {
                                    echo 'Guardado';
                                } else {
                                    echo 'Hubo un error al guardar';
                                }
                            } else {
                                echo 'Hubo un error con la imagen';
                            }
                        } else {
                            echo "Hubo un error en los datos enviados";
                        }
                        break;
                    case "modificacion":
                        if (isset($_POST["legajo"]) && !is_null($dao->obtenerPorId("legajo", $_POST["legajo"]))) {
                            //IMAGEN
                            if (isset($_FILES["imagen"]) && isImage($_FILES["imagen"]) && tamanoValidoEnMb(($_FILES["imagen"]), 2)) {
                                //Backup Imagen
                                $rutaAntigua = ($dao->obtenerPorId("legajo", $_POST["legajo"]))->imagen;
                                $arrayAux = explode("/", $rutaAntigua);
                                $rutaNueva = "./imagenes/backup/" . end($arrayAux);
                                rename($rutaAntigua, $rutaNueva);
                                //Modificacion
                                $tmpName = $_FILES["imagen"]["tmp_name"];
                                $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
                                $filename = "./imagenes/" . $_POST["legajo"] . "." . $extension;
                                $rta = move_uploaded_file($tmpName, $filename);
                                if ($rta === true) {
                                    $rta = $dao->modificar("legajo", $_POST["legajo"], "imagen", $filename);
                                    if ($rta === true) {
                                        echo 'Imagen modificada';
                                    } else {
                                        echo 'Hubo un error al modificar la imagen';
                                    }
                                } else {
                                    echo 'Hubo un error con la imagen';
                                }
                            }
                            //NOMBRE
                            if (isset($_POST["nombre"])) {
                                $rta = $dao->modificar("legajo", $_POST["legajo"], "nombre", $_POST["nombre"]);
                                if ($rta === true) {
                                    echo PHP_EOL . 'Nombre modificado';
                                } else {
                                    echo PHP_EOL . 'Hubo un error al modificar el nombre';
                                }
                            }
                            //APELLIDO
                            if (isset($_POST["apellido"])) {
                                $rta = $dao->modificar("legajo", $_POST["legajo"], "apellido", $_POST["apellido"]);
                                if ($rta === true) {
                                    echo PHP_EOL . 'Apellido modificado';
                                } else {
                                    echo PHP_EOL . 'Hubo un error al modificar el apellido';
                                }
                            }
                        } else {
                            echo "Hubo un error en los datos enviados";
                        }
                        break;
                    case "borrar":
                        if (isset($_POST["legajo"]) && !is_null($dao->obtenerPorId("legajo", $_POST["legajo"]))) {
                            unlink(($dao->obtenerPorId("legajo", $_POST["legajo"]))->imagen);
                            $rta = $dao->borrar("legajo", $_POST["legajo"]);
                            if ($rta == true) {
                                echo 'Borrado';
                            } else {
                                echo 'Hubo un error al borrar';
                            }
                        } else {
                            echo "Hubo un error en los datos enviados";
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
} catch (Exception $e) {
    echo $e->getMessage();
    //throw $e;
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

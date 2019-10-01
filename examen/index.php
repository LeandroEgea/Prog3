<?php
include './clases/usuario.php';
include './clases/info.php';
include './controller/usuarioController.php';
include './controller/infoController.php';
include './clases/genericDao.php';

$request = ($_SERVER['REQUEST_METHOD']);
$usuarioController = new UsuarioController();
$infoController = new InfoController();

switch ($request) {
    case "POST":
        if (isset($_POST["case"])) {
            $infoController->guardar($_POST["case"], $_SERVER['REMOTE_ADDR']);
            switch ($_POST["case"]) {
                case "cargarUsuario":
                    if (isset($_POST["legajo"]) && isset($_POST["email"]) && isset($_POST["nombre"])
                        && isset($_POST["clave"]) && isset($_FILES["fotoUno"]) && isset($_FILES["fotoDos"])) {
                        $usuarioController->cargarUsuario($_POST["legajo"], $_POST["email"], $_POST["nombre"],
                            $_POST["clave"], $_FILES["fotoUno"], $_FILES["fotoDos"]);
                    } else {
                        echo '{"error":"Hubo un error en los datos enviados"}';
                    }
                    break;
                case "modificarUsuario":
                    if (isset($_POST["legajo"])) {
                        $usuarioController->modificarUsuario($_POST, $_FILES);
                    } else {
                        echo '{"error":"indique Legajo"}';
                    }
                    break;
            }
        } else {
            echo '{"error":"Indique el case correctamente"}';
        }
        break;
    case "GET":
        if (isset($_GET["case"])) {
            $infoController->guardar($_GET["case"], $_SERVER['REMOTE_ADDR']);
            switch ($_GET["case"]) {
                case "login":
                    if (isset($_GET["legajo"]) && isset($_GET["clave"])) {
                        echo $usuarioController->login($_GET["legajo"], $_GET["clave"]);
                    } else {
                        echo '{"error":"no se indico legajo y clave"}';
                    }
                    break;
            }
        } else {
            echo '{"error":"Indique el case correctamente"}';
        }
        break;
}

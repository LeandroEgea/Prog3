<?php
include './clases/alumno.php';
include './clases/materia.php';
include './clases/inscripcion.php';
include './controller/alumnoController.php';
include './controller/materiaController.php';
include './controller/inscripcionController.php';
include './clases/genericDao.php';

$request = ($_SERVER['REQUEST_METHOD']);
$alumnoController = new AlumnoController();
$materiaController = new MateriaController();
$inscripcionController = new InscripcionController();

try {
    switch ($request) {
        case "POST":
            if (isset($_POST["case"])) {
                switch ($_POST["case"]) {
                    case "cargarAlumno":
                        if (isset($_POST["nombre"]) && isset($_POST["apellido"]) &&
                            isset($_POST["email"]) && isset($_FILES["foto"])) {
                            $alumnoController->cargarAlumno($_POST["nombre"], $_POST["apellido"],
                                $_POST["email"], $_FILES["foto"]);
                        } else {
                            echo "Hubo un error en los datos enviados";
                        }
                        break;
                    case "cargarMateria":
                        if (isset($_POST["nombre"]) && isset($_POST["codigo"]) &&
                            isset($_POST["cupo"]) && isset($_POST["aula"])) {
                            $materiaController->cargarMateria($_POST["nombre"], $_POST["codigo"],
                                $_POST["cupo"], $_POST["aula"]);
                        } else {
                            echo "Hubo un error en los datos enviados";
                        }
                        break;
                    case "modificarAlumno":
                        if (isset($_POST["email"])) {
                            $alumnoController->modificarAlumno($_POST, $_FILES);
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
            if (isset($_GET["case"])) {
                switch ($_GET["case"]) {
                    case "consultarAlumno":
                        if (isset($_GET["apellido"])) {
                            echo $alumnoController->consultarAlumno($_GET["apellido"]);
                        } else {
                            echo 'Indique el apellido';
                        }
                        break;
                    case "inscripciones":
                        if (!isset($_GET["codigoMateria"]) && !isset($_GET["apellidoAlumno"])) {
                            echo $inscripcionController->mostrarInscripciones();
                        } else {
                            echo $inscripcionController->mostrarInscripcionesFiltro($_GET);
                        }
                        break;
                    case "inscribirAlumno":
                        if (isset($_GET["nombreAlumno"]) && isset($_GET["apellidoAlumno"]) &&
                            isset($_GET["emailAlumno"]) && isset($_GET["nombreMateria"]) &&
                            isset($_GET["codigoMateria"])) {
                            $inscripcionController->inscribirAlumno($_GET["nombreAlumno"],
                                $_GET["apellidoAlumno"], $_GET["emailAlumno"], $_GET["nombreMateria"],
                                $_GET["codigoMateria"]);
                        } else {
                            echo "Hubo un error en los datos enviados";
                        }
                        break;
                    case "mostrarAlumnos":
                        $alumnoController->mostrarAlumnos();
                        break;
                }
            } else {
                echo 'Indique el case correctamente';
            }
            break;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

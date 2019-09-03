<?php
INCLUDE './clases/persona.php';
INCLUDE './clases/alumno.php';
INCLUDE './clases/alumnosDao.php';

$request = ($_SERVER['REQUEST_METHOD']);
$dao = new AlumnosDao();

switch($request){
    case "POST" : 
        if(isset($_POST["Nombre"]) && isset($_POST["Dni"]) && isset($_POST["Legajo"]) && isset($_POST["Cuatrimestre"])){
            $alumno = new Alumno($_POST["Nombre"],$_POST["Dni"],$_POST["Legajo"],$_POST["Cuatrimestre"]);
            $dao->guardar($alumno);
        }
        break;

    case "GET" : 
        echo($dao->listar());
        break;

    case "PUT" : 
        if(isset($_GET["Nombre"]) && isset($_GET["Legajo"])){
            $dao->modificar($_GET["Legajo"], $_GET["Nombre"]);
        }
        break;

    case "DELETE":
        if(isset($_GET["Legajo"])){
            $dao->borrar($_GET["Legajo"]);
        }
        break;
        
}
//var_dump($_SESSION["Alumnos"]);
//distintos get, put, post
?>





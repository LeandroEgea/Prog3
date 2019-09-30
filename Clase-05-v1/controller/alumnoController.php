<?php
class AlumnoController
{
    //TODO deberia ser estatico la clase y el atributo
    public $alumnosDao;

    public function __construct()
    {
        $this->alumnosDao = new GenericDao('./alumnos.txt');
    }

    //TODO Checkear que no este usado el email
    function cargarAlumno($nombre, $apellido, $email, $foto) {
        if($this->isImage($foto) && $this->tamanoValidoEnMb($foto, 2)) {
            $tmpName = $foto["tmp_name"];
            $extension = pathinfo($foto["name"], PATHINFO_EXTENSION);
            $filename = "./imagenes/" . $email . "." . $extension;
            $rta = move_uploaded_file($tmpName, $filename);
            if ($rta === true) {
                $alumno = new Alumno($nombre, $apellido, $email, $filename);
                $rta = $this->alumnosDao->guardar($alumno);
                if ($rta === true) {
                    echo 'Guardado';
                } else {
                    echo 'Hubo un error al guardar';
                }
            } else {
                echo 'Hubo un error con la fotos';
            }
        }
    }

    function consultarAlumno($apellido) {
        return $this->alumnosDao->getByAttributeCaseInsensitive("apellido", $apellido);
    }
    
    function modificarAlumno($email, $POST, $FILES)
    {
        //IMAGEN
        // !is_null($dao->obtenerPorId("email", $_POST["email"]))
        if (isset($FILES["foto"]) && isImage($FILES["foto"]) && tamanoValidoEnMb(($FILES["foto"]), 2)) {
            //Backup Imagen
                $hoy = date("d-m-Y_h:m");
                $rutaAntigua = ($this->alumnosDao->obtenerPorId("email", $POST["email"]))->foto;
                $array = explode(".", $rutaAntigua);//ver que es esto
                var_dump($array);
                $rutaNueva = "./imagenes/backUpFotos/" . end($array);
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
    }

    function isImage($imagen): bool
    {
        if (explode("/", $imagen["type"])[0] == "image") {
            return true;
        } else {
            throw new Exception("No es un archivo de imagen");
        }
    }
    
    function tamanoValidoEnMb($archivo, $mb): bool
    {
        if (($archivo["size"]) < ($mb * 1024 * 1024)) {
            return true;
        } else {
            throw new Exception("Tamaño maximo $mb mb");
        }
    }
}
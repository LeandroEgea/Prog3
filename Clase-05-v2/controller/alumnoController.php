<?php
class AlumnoController
{
    public $alumnosDao;

    public function __construct()
    {
        $this->alumnosDao = new GenericDao('./alumnos.txt');
    }

    public function cargarAlumno($nombre, $apellido, $email, $foto)
    {
        //Valido que el alumno existe en el objeto
        $alumnoExistente = $this->alumnosDao->getObjectByKeyCaseInsensitive("email", $email);
        if ($this->isImage($foto) && $this->tamanoValidoEnMb($foto, 2) && is_null($alumnoExistente)) {
            $tmpName = $foto["tmp_name"];
            $extension = pathinfo($foto["name"], PATHINFO_EXTENSION);
            $filename = "./imagenes/" . $email . "." . $extension;
            $rta = move_uploaded_file($tmpName, $filename);
            if ($rta === true) {
                $alumno = new Alumno($nombre, $apellido, $email, $filename);
                $rta = $this->alumnosDao->guardar($alumno);
                if ($rta === true) {
                    echo 'Se cargo el alumno ' . $alumno->nombre . " " . $alumno->apellido;
                } else {
                    echo 'Hubo un error al guardar';
                }
            } else {
                echo 'Hubo un error con la fotos';
            }
        } else {
            echo "No se puede cargar el alumno";
        }
    }

    public function consultarAlumno($apellido)
    {
        return $this->alumnosDao->getObjectsByKeyCaseInsensitive("apellido", $apellido);
    }

    public function modificarAlumno($POST, $FILES)
    {
        $alumnoAModificar = $this->alumnosDao->getObjectByKeyCaseInsensitive("email", $POST["email"]);
        if (!is_null($alumnoAModificar)) {
            $rta === true;
            /// Me guardo el valor actual de todas la claves del usuario
            $nombreAux = $alumnoAModificar->nombre;
            $apellidoAux = $alumnoAModificar->apellido;
            $fotoAux = $alumnoAModificar->foto;
            if (array_key_exists("apellido", $POST) && $apellidoAux != $POST["apellido"]) {
                $apellidoAux = $POST["apellido"];
            }
            if (array_key_exists("nombre", $POST) && $nombreAux != $POST["nombre"]) {
                $nombreAux = $POST["nombre"];
            }
            if (array_key_exists("foto", $FILES)) {
                // Me guardo la hora actual
                $fechaBkp = date("d-m-Y_H_i");
                //transormo en un array todo lo que este separado por un punto
                $array = explode(".", $alumnoAModificar->foto);
                //Genero la ruta para almacenar la foto de backup
                $rutaParaBkp = "./imagenes/backUpFotos/" . $alumnoAModificar->apellido . $fechaBkp . "." . end($array);
                //Backup Imagen
                rename($alumnoAModificar->foto, $rutaParaBkp);
                //Modificacion
                $tmpName = $FILES["foto"]["tmp_name"];
                $extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
                // Cambio el nombre de la foto y coloco email.extension
                $fotoAux = "./imagenes/" . $POST["email"] . "." . $extension;
                $rta = move_uploaded_file($tmpName, $fotoAux);
            }
            if ($rta === true) {
                $alumnoAux = new Alumno($nombreAux, $apellidoAux, $POST["email"], $fotoAux);
                $rta = $this->alumnosDao->modificar("email", $POST["email"], $alumnoAux);
                if ($rta) {
                    echo "Modificacion realizada";
                } else {
                    echo "No se pudo realizar la modificacion";
                }
            } else {
                echo "Hubo un problema con la imagen";
            }

        } else {
            echo "No se encontro el alumno";
        }
    }

    public function mostrarAlumnos()
    {
        echo $this->alumnosDao->listar();
    }

    public function isImage($imagen): bool
    {
        if (explode("/", $imagen["type"])[0] == "image") {
            return true;
        } else {
            throw new Exception("No es un archivo de imagen");
        }
    }

    public function tamanoValidoEnMb($archivo, $mb): bool
    {
        if (($archivo["size"]) < ($mb * 1024 * 1024)) {
            return true;
        } else {
            throw new Exception("Tamaño maximo $mb mb");
        }
    }
}

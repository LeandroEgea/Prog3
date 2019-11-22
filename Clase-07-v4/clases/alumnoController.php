<?php
class AlumnoController
{
    public $alumnosDao; //se crea un alumnosDao para luego poder usar las funciones del GenericDao

    public function __construct()
    {
        $this->alumnosDao = new GenericDao('./alumnos.txt'); //instancia alumnosDao con el constructor del GenericDao pasandole el archivo de alumnos
    }

    
    //Carga un alumno en el archivo de alumnos
    function cargarAlumno($nombre, $apellido, $email, $foto) 
    {
        //Valido que el alumno que se desea cargar no existe en el archivo de alumnos   
        $alumnoExistente = $this->alumnosDao->getObjectByKeyCaseInsensitive("email", $email); //busca el alumno por el email recibido por parametro, en el archivo de alumnos
        if ($this->isImage($foto) && $this->tamanoValidoEnMb($foto, 2) && is_null($alumnoExistente)) { //valido que sea una imagen, que no supere los 2 mb y que no haya un alumno en el archivo de alumno
            $tmpName = $foto["tmp_name"]; //obtiene el nombre temporal de la foto recibida por parametro
            $extension = pathinfo($foto["name"], PATHINFO_EXTENSION); // obtiene la extencion de la foto recibida por parametro
            $filename = "./imagenes/" . $email . "." . $extension; //se crea el filename
            $rta = move_uploaded_file($tmpName, $filename);
            if ($rta === true) {
                $alumno = new Alumno($nombre, $apellido, $email, $filename); //se crea un alumno con los datos recibidos por parametro
                $rta = $this->alumnosDao->guardar($alumno); //se guarda dicho alumno
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

    //Trae todos los alumnos que coincidan con el apellido recibido por parametro, del archivo de alumnos
    function consultarAlumno($apellido)
    {
        var_dump($this->alumnosDao);
        return $this->alumnosDao->getObjectByKeyCaseInsensitive("apellido", $apellido);
    }

    //Modifica un alumno del archivo de alumnos
    function modificarAlumno($POST, $FILES) //El post se usa para obtener los datos del alumno y el files para los datos de la foto
    {
            $alumnoAModificar = $this->alumnosDao->getObjectByKeyCaseInsensitive("email", $POST["email"]); //obtiene el alumno que se desea modificar mediante la key email y el value de ese email ej: "gaby@gmail.com"
            if(!is_null($alumnoAModificar)) // si se obtuvo
            {
                $rta === true;
                /// Me guardo el valor actual de todas la claves del usuario, si el usuario deseará modificarlas, se pisaran.
                $nombreAux = $alumnoAModificar->nombre;
                $apellidoAux = $alumnoAModificar->apellido;
                $fotoAux = $alumnoAModificar->foto;
                
                //pregunta si en el POST que se recibio por parametro contiene la key "apellido" para verificar si es lo que se quiere modificar y ademas pregunta si el apellido del alumno obtenido por email es distinto del apellido que hay en el POST
                if (array_key_exists("apellido", $POST) && $apellidoAux != $POST["apellido"]) { 
                    $apellidoAux = $POST["apellido"]; //pisa el apellido del alumno obtenido con el apellido recibido en POST
                }
                //pregunta si en el POST que se recibio por parametro contiene la key "nombre" para verificar si es lo que se quiere modificar y ademas pregunta si el nombre del alumno obtenido por email es distinto del nombre que hay en el POST
                if (array_key_exists("nombre", $POST)&& $nombreAux != $POST["nombre"]) {
                    $nombreAux = $POST["nombre"];//pisa el nombre del alumno obtenido con el nombre recibido en POST
                }
                if (array_key_exists("foto", $FILES)) {
                    $fechaBkp = date("d-m-Y_H_i");// Me guardo la hora actual
                    $array = explode(".", $alumnoAModificar->foto); //transformo en un array todo lo que este separado por un punto
                    $rutaParaBkp = "./imagenes/backUpFotos/" . 
                    $alumnoAModificar->apellido . $fechaBkp . "." . end($array);//Genero la ruta para almacenar la foto de backup
                    //Backup Imagen
                    rename($alumnoAModificar->foto, $rutaParaBkp);// Hago backup de la foto
                    //Modificacion
                    $tmpName = $FILES["foto"]["tmp_name"];
                    $extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
                    $fotoAux = "./imagenes/" . $POST["email"] . "." . $extension; // Cambio el nombre de la foto y coloco email.extension
                    $rta = move_uploaded_file($tmpName, $fotoAux);
                } 
                if($rta === true)
                {
                    $alumnoAux = new Alumno($nombreAux, $apellidoAux, $POST["email"], $fotoAux); //se crea un nuevo alumno con los datos ya modificados
                    $rta = $this->alumnosDao->modificar("email", $POST["email"], $alumnoAux);
                    if($rta)
                    {
                        echo "Modificacion realizada";
                    }
                    else{
                        echo "No se pudo realizar la modificacion";
                    }
                }
                else
                {
                    echo "Hubo un problema con la foto";
                }
            }
            else{
                echo "No se encontro el alumno";
            }
                
    }

    //Lista todos los alumnos del archivo de alumnos
    function mostrarAlumnos()
    {
        return $this->alumnosDao->listar();
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

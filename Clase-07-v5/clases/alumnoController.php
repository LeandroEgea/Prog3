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
        if (is_null($alumnoExistente)) { //valido que sea una imagen, que no supere los 2 mb y que no haya un alumno en el archivo de alumno
            $tmpName = $foto->getClientFilename(); //obtiene el nombre temporal de la foto recibida por parametro
            $extension = pathInfo($tmpName, PATHINFO_EXTENSION); // obtiene la extencion de la foto recibida por parametro
            $filename = "./imagenes/" . $email . "." . $extension; //se crea el filename
            $foto->moveTo($filename);

            $alumno = new Alumno($nombre, $apellido, $email, $filename); //se crea un alumno con los datos recibidos por parametro
            $rta = $this->alumnosDao->guardar($alumno); //se guarda dicho alumno
            if ($rta === true) {
                echo 'Se cargo el alumno ' . $alumno->nombre . " " . $alumno->apellido;
            } else {
                echo 'Hubo un error al guardar';
            }
        } else {
            echo "No se puede cargar el alumno";
        }
    }

    //Trae todos los alumnos que coincidan con el apellido recibido por parametro, del archivo de alumnos
    function consultarAlumno($apellido)
    {
        return $this->alumnosDao->getObjectByKeyCaseInsensitive("apellido", $apellido);
    }

    //Modifica un alumno del archivo de alumnos
    function modificarAlumno($archivos, $parametros) //El post se usa para obtener los datos del alumno y el files para los datos de la foto
    {
        $alumnoAModificar = $this->alumnosDao->getObjectByKeyCaseInsensitive("email", $parametros["email"]); //obtiene el alumno que se desea modificar mediante la key email y el value de ese email ej: "gaby@gmail.com"
            if(!is_null($alumnoAModificar)) // si se obtuvo
            {
                /// Me guardo el valor actual de todas la claves del usuario, si el usuario deseará modificarlas, se pisaran.
                $nombreAux = $alumnoAModificar->nombre;
                $apellidoAux = $alumnoAModificar->apellido;
                $fotoAux = $alumnoAModificar->foto;
                
                //pregunta si en el POST que se recibio por parametro contiene la key "apellido" para verificar si es lo que se quiere modificar y ademas pregunta si el apellido del alumno obtenido por email es distinto del apellido que hay en el POST
                if (array_key_exists("apellido", $parametros) && $apellidoAux != $parametros["apellido"]) { 
                    $apellidoAux = $parametros["apellido"]; //pisa el apellido del alumno obtenido con el apellido recibido en POST
                }
                //pregunta si en el POST que se recibio por parametro contiene la key "nombre" para verificar si es lo que se quiere modificar y ademas pregunta si el nombre del alumno obtenido por email es distinto del nombre que hay en el POST
                if (array_key_exists("nombre", $parametros) && $nombreAux != $parametros["nombre"]) {
                    $nombreAux = $parametros["nombre"];//pisa el nombre del alumno obtenido con el nombre recibido en POST
                }
                if (array_key_exists("foto", $archivos)) {
                    $rta = true;
                    $fechaBkp = date("d-m-Y_H_i");// Me guardo la hora actual
                    $array = explode(".", $alumnoAModificar->foto); //transformo en un array todo lo que este separado por un punto
                    $rutaParaBkp = "./imagenes/backUpFotos/" . 
                    $alumnoAModificar->apellido . $fechaBkp . "." . end($array);//Genero la ruta para almacenar la foto de backup
                    //Backup Imagen
                    rename($alumnoAModificar->foto, $rutaParaBkp);// Hago backup de la foto
                    //Modificacion
                    $foto = $archivos["foto"];
                    $tmpName = $foto->getClientFilename();
                    $extension = pathinfo($tmpName, PATHINFO_EXTENSION);
                    $alumnoAModificar->foto = "./imagenes/" . $parametros["email"] . "." . $extension;
                    $foto->moveTo($fotoAux);
                } 
                $alumnoAux = new Alumno($nombreAux, $apellidoAux, $parametros["email"], $fotoAux); //se crea un nuevo alumno con los datos ya modificados
                $rta = $this->alumnosDao->modificar("email", $parametros["email"], $alumnoAux);
                if($rta)
                {
                    echo "Modificacion realizada";
                }
                else{
                    echo "No se pudo realizar la modificacion";
                }
            }
            else{
                echo "No se encontro el alumno";
            }
                
    }

    function borrarAlumno ($email){
        return $this->alumnosDao->borrar("email", $email);
    }

    //Lista todos los alumnos del archivo de alumnos
    function mostrarAlumnos()
    {
        return $this->alumnosDao->listar();
    }

    function mostrarAlumnosExceptoUnCampo($campo)
    {
        $alumnosJSON = $this->alumnosDao->listar();
        $alumnos = json_decode($alumnosJSON); 
            foreach ($alumnos as $key => $alumno) { 
                    unset($alumno->$campo);
                }
        return json_encode($alumnos);

    }

    function isImage($imagen)
    {
        if (explode("/", $imagen["type"])[0] == "image") {
            return true;
        } else {
            throw new Exception("No es un archivo de imagen");
        }
    }

    function tamanoValidoEnMb($archivo, $mb)
    {
        if (($archivo["size"]) < ($mb * 1024 * 1024)) {
            return true;
        } else {
            throw new Exception("Tamaño maximo $mb mb");
        }
    }
}

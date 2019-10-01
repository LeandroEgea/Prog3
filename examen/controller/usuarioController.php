<?php
class UsuarioController
{
    public $usuariosDao;

    public function __construct()
    {
        $this->usuariosDao = new GenericDao('./usuarios.txt');
    }

    public function cargarUsuario($legajo, $email, $nombre, $clave, $fotoUno, $fotoDos)
    {
        //Valido que el usuario no exista
        $usuarioExistente = $this->usuariosDao->getObjectByKeyCaseInsensitive("legajo", $legajo);
        if (is_null($usuarioExistente) && $this->isImage($fotoUno) && $this->tamanoValidoEnMb($fotoUno, 2)
            && $this->isImage($fotoDos) && $this->tamanoValidoEnMb($fotoDos, 2)) {
            $rtaUno = $this->guardarFoto($fotoUno, $legajo, 1);
            $rtaDos = $this->guardarFoto($fotoDos, $legajo, 2);
            if ($rtaUno !== false && $rtaDos !== false) {
                $usuario = new Usuario($legajo, $email, $nombre, $clave, $rtaUno, $rtaDos);
                $rta = $this->usuariosDao->guardar($usuario);
                if ($rta === true) {
                    echo '{"message":"Se cargo el usuario correctamente"}';
                } else {
                    echo '{"error":"Hubo un error al guardar"}';
                }
            } else {
                echo '{"error":"Hubo un error con la fotos"}';
            }
        } else {
            echo '{"error":"No se puede cargar el usuario"}';
        }
    }

    public function guardarFoto($foto, $legajo, $numeroFoto)
    {
        $tmpName = $foto["tmp_name"];
        $extension = pathinfo($foto["name"], PATHINFO_EXTENSION);
        $filename = "./img/fotos/" . $legajo . "-" . $numeroFoto . "." . $extension;
        $rta = move_uploaded_file($tmpName, $filename);
        if ($rta === true) {
            return $filename;
        } else {
            return false;
        }
    }

    public function modificarUsuario($POST, $FILES)
    {
        $usuarioAModificar = $this->usuariosDao->getObjectByKeyCaseInsensitive("legajo", $POST["legajo"]);
        if (!is_null($usuarioAModificar)) {
            $rta = true;
            /// Me guardo el valor actual de todas la claves del usuario
            $emailAux = $usuarioAModificar->email;
            $nombreAux = $usuarioAModificar->nombre;
            $claveAux = $usuarioAModificar->clave;
            $fotoUnoAux = $usuarioAModificar->fotoUno;
            $fotoDosAux = $usuarioAModificar->fotoDos;
            if (array_key_exists("email", $POST)) {
                $emailAux = $POST["email"];
            }
            if (array_key_exists("nombre", $POST)) {
                $nombreAux = $POST["nombre"];
            }
            if (array_key_exists("clave", $POST)) {
                $claveAux = $POST["clave"];
            }
            if (array_key_exists("fotoUno", $FILES)) {
                $rta = $this->modificarFoto($usuarioAModificar, $FILES["fotoUno"], 1, "fotoUno");
                if ($rta !== false) {
                    $fotoUnoAux = $rta;
                }
            }
            if (array_key_exists("fotoDos", $FILES)) {
                $rta = $this->modificarFoto($usuarioAModificar, $FILES["fotoDos"], 2, "fotoDos");
                if ($rta !== false) {
                    $fotoDosAux = $rta;
                }
            }
            if ($rta !== true) {
                $usuarioAux = new Usuario($POST["legajo"], $emailAux, $nombreAux, $claveAux, $fotoUnoAux, $fotoDosAux);
                $rta = $this->usuariosDao->modificar("legajo", $POST["legajo"], $usuarioAux);
                if ($rta) {
                    echo '{"message":"Modificacion realizada"}';
                } else {
                    echo '{"error":"No se pudo realizar la modificacion"}';
                }
            } else {
                echo '{"error":"Hubo un problema con la imagen"}';
            }
        } else {
            echo '{"error":"No se encuentra el usuario"}';
        }
    }

    public function modificarFoto($usuarioAModificar, $foto, $numeroFoto, $fotoAModificar)
    {
        $fechaBkp = date("d-m-Y_H_i");
        $array = explode("/img/fotos/", $usuarioAModificar->$fotoAModificar);
        $rutaParaBkp = "./img/backup/" . $fechaBkp . "-" . end($array);
        //Backup Imagen
        rename($usuarioAModificar->$fotoAModificar, $rutaParaBkp);
        //Modificacion
        return $this->guardarFoto($foto, $usuarioAModificar->legajo, $numeroFoto);
    }

    public function isImage($imagen): bool
    {
        if (explode("/", $imagen["type"])[0] == "image") {
            return true;
        } else {
            echo '{"error":"No es un archivo de imagen"}';
            return false;
        }
    }

    public function tamanoValidoEnMb($archivo, $mb): bool
    {
        if (($archivo["size"]) < ($mb * 1024 * 1024)) {
            return true;
        } else {
            echo '{"error":"Supera tamano maximo"}';
            return false;
        }
    }

    public function login($legajo, $clave)
    {
        $usuarioExistente = $this->usuariosDao->getObjectByKeyCaseInsensitive("legajo", $legajo);
        if (!is_null($usuarioExistente)) {
            if (strtolower($usuarioExistente->clave) == strtolower($clave)) {
                return json_encode($usuarioExistente);
            } else {
                echo '{"error":"Clave Incorrecta"}';
            }
        } else {
            echo '{"error":"No se encontro el legajo"}';
        }
    }
}

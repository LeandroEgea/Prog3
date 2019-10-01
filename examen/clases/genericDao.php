<?php
class GenericDao
{
    public $archivo;

    public function __construct($archivo)
    {
        $this->archivo = $archivo;
    }

    public function listar()
    {
        //Valido que el archivo este creado y que el size sea distinto de 0
        if (file_exists($this->archivo) && filesize($this->archivo) != 0) {
            try {
                $archivo = fopen($this->archivo, "r");
                return fread($archivo, filesize($this->archivo));
            } catch (Exception $e) {
                echo '{"error":"No se pudo listar"}';
            } finally {
                fclose($archivo);
            }
        } else {
            return "";
        }
    }

    public function getObjectByKeyCaseInsensitive($attrKey, $attrValue)
    {
        if (file_exists($this->archivo) && filesize($this->archivo) != 0) {
            try {
                $objects = json_decode($this->listar());
                foreach ($objects as $object) {
                    //Comparo todo en minuscula
                    if (strtolower($object->$attrKey) == strtolower($attrValue)) {
                        return $object;
                    }
                }
                return null;
            } catch (Exception $e) {
                echo '{"error":"No se pudo obtener"}';
            }
        } else {
            return null;
        }
    }

    public function guardar($object): bool
    {
        try {
            $objects = [];
            if (file_exists($this->archivo) && filesize($this->archivo) != 0) {
                $jsonDecoded = json_decode($this->listar());
                //Valido si el array de json esta vacio
                if (count($jsonDecoded) > 0) {
                    //Si no está vacio, le pongo los objetos de json_decode
                    $objects = $jsonDecoded;
                }
            }
            $archivo = fopen($this->archivo, "w");
            //Pusheo mi objeto creado al array de objetos json
            array_push($objects, $object);
            //Codifico el array como json
            fwrite($archivo, json_encode($objects));
            return true;
        } catch (Exception $e) {
            echo '{"error":"No se pudo guardar"}';
        } finally {
            fclose($archivo);
        }
    }

    public function modificar($idKey, $idValue, $objeto): bool
    {
        try {
            $objects = json_decode($this->listar());
            $rta = false;
            for ($i = 0; $i < count($objects); $i++) {
                if ($objects[$i]->$idKey == $idValue) {
                    $objects[$i] = $objeto;
                    $rta = true;
                    break;
                }
            }
            if ($rta === true) {
                $archivo = fopen($this->archivo, "w");
                return fwrite($archivo, json_encode($objects));
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        } finally {
            fclose($archivo);
        }
    }
}

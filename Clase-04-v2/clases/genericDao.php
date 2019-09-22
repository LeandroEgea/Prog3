<?php
class GenericDao
{
    public $archivo;

    public function __construct($archivo)
    {
        $this->archivo = $archivo;
    }

    public function obtenerPorId($idKey, $idValue)
    {
        $objects = json_decode($this->listar());
        foreach ($objects as $object) {
            if ($object->$idKey == $idValue) {
                return $object;
            }
        }
    }

    public function listar()
    {
        try {
            $archivo = fopen($this->archivo, "r");
            return fread($archivo, filesize($this->archivo));
        } catch (Exception $e) {
            throw new Exception("No se pudo listar", 0, $e);
        } finally {
            fclose($archivo);
        }
    }

    public function guardar($object): bool
    {
        try {
            $objects = json_decode($this->listar());
            $archivo = fopen($this->archivo, "w");
            array_push($objects, $object);
            fwrite($archivo, json_encode($objects));
            return true;
        } catch (Exception $e) {
            throw new Exception("No se pudo guardar", 0, $e);
        } finally {
            fclose($archivo);
        }
    }

    public function borrar($idKey, $idValue): bool
    {
        try {
            $objects = json_decode($this->listar());
            $archivo = fopen($this->archivo, "w");
            foreach ($objects as $key => $object) {
                if ($object->$idKey == $idValue) {
                    unset($objects[$key]);
                    break;
                }
            }
            fwrite($archivo, json_encode($objects));
        } catch (Exception $e) {
            throw new Exception("No se pudo borrar", 0, $e);
        } finally {
            fclose($archivo);
        }
    }

    public function modificar($idKey, $idValue, $changeKey, $changeValue): bool
    {
        try {
            $objects = json_decode($this->listar());
            $archivo = fopen($this->archivo, "w");
            foreach ($objects as $object) {
                if ($object->$idKey == $idValue) {
                    $object->$changeKey = $changeValue;
                    break;
                }
            }
            fwrite($archivo, json_encode($objects));
        } catch (Exception $e) {
            throw new Exception("No se pudo modificar", 0, $e);
        } finally {
            fclose($archivo);
        }
    }
}

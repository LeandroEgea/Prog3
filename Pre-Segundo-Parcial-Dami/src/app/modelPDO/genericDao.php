<?php
class GenericDao
{
    public $archivo;
    public function __construct($archivo)
    {
        if(file_exists($archivo))
        {
            $this->archivo = $archivo;
        }
        else {
            $aux = fopen($archivo,"w");
            fwrite($aux," ");
            $this->archivo = $archivo;
            fclose($aux);
        }
    }
    public function obtenerPorId($idKey, $idValue)
    {
        $objects = json_decode($this->listar());
        foreach ($objects as $object) {
            if ($object->$idKey == $idValue) {
                return $object;
            }
        }
        return null;
    }
    // Abrir archivo en modo lectura
    public function listar()
    {
        if (file_exists($this->archivo)) {
            try {
                $archivo = fopen($this->archivo, "r");
                return fread($archivo, filesize($this->archivo));
            } catch (Exception $e) {
                throw new Exception("No se pudo listar", 0, $e);
            } finally {
                fclose($archivo);
            }
        } else {
            return "";
        }
    }
    // Verifica si un objeto ya existe en el JSON por medio de un campo especifico
    public function exists($attrKey, $attrValue)
    {
        try
        {
            $objects = json_decode($this->listar());
            if(!is_null($objects))
            {
                foreach ($objects as $object) {
                    //Comparo todo en minuscula
                    if (strtolower($object->$attrKey) == strtolower($attrValue)) {
                        return true;
                    }
                }
                return false;
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("JSON vacio", 1);
        }
    }
    public function getObjects($attrKey, $attrValue)
    {
        try {
            $objects = json_decode($this->listar());
            $retorno = array();
            if(!is_null($objects)){
                foreach ($objects as $object) {
                    //Comparo todo en minuscula
                    if (strtolower($object->$attrKey) == strtolower($attrValue)) {
                        array_push($retorno, $object);
                    }
                }
                if (count($retorno) > 0) {
                    return json_encode($retorno); // Convierto en JSON
                }
                return null;
            }
        } catch (Exception $e) {
            throw new Exception("Error al buscar objeto.", 0, $e);
        }
    }
    public function getObject($attrKey, $attrValue)
    {
        try {
            $objects = json_decode($this->listar());
            $retorno = array();
            if(!is_null($objects)){
                foreach ($objects as $object) {
                    //Comparo todo en minuscula
                    if (strtolower($object->$attrKey) == strtolower($attrValue)) {
                        return $object;
                    }
                }
                return null;
            }
        } catch (Exception $e) {
            throw new Exception("Error al buscar objeto.", 0, $e);
        }
    }
    // Guarda un nuevo objeto en el archivo JSON
    public function guardar($object)
    {
        try
        {
            $objects = [];
            $jsonDecoded = json_decode($this->listar());
            //Valido si el array de json esta vacio
            if (!is_null($jsonDecoded)) {
                //Si está vacio, lo formateo para que sea un array de objetos json.
                $objects = $jsonDecoded;
            }
            //Pusheo mi objeto creado al array de objetos json
            array_push($objects, $object);
            //Codifico el array como json
            $aux = fopen($this->archivo, "w");
            fwrite($aux, json_encode($objects));
            return true;
        }
        catch (Exception $e)
        {
            throw new Exception("No se pudo guardar", 0, $e);
        }
        finally
        {
            fclose($aux);
        }
    }
    public function modificar($idKey, $idValue, $objeto)
    {
     try {
         $objects = json_decode($this->listar());
         if(!is_null($objects))
         {
             $archivo = fopen($this->archivo, "w");
             for ($i = 0; $i < count($objects); $i++) {
                 if ($objects[$i]->$idKey == $idValue) {
                     $objects[$i] = $objeto;
                     return fwrite($archivo, json_encode($objects));
                 }
             }
         }
         else {
             return false;
         }
     } catch (Exception $e) {
         throw new Exception("No se pudo modificar", 0, $e);
     } finally {
         fclose($archivo);
     }
    }
    public function borrar($idKey, $idValue)
    {
        try {
            $retorno = false;
            $objects = json_decode($this->listar());
            if(!is_null($objects))
            {
                $archivo = fopen($this->archivo, "w");
                foreach ($objects as $key => $object) {
                    if ($object->$idKey == $idValue) {
                        echo $objects[$key];
                        unset($objects[$key]);
                        break;
                    }
                }
                return fwrite($archivo, json_encode($objects));
            }
        } catch (Exception $e) {
            throw new Exception("No se pudo borrar", 0, $e);
        } finally {
            fclose($archivo);
        }
    }
}
 ?>
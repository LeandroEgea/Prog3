<?php
class GenericDao
{
    public $archivo;

    public function __construct($archivo)
    {
        $this->archivo = $archivo;
    }

    //Lista todo lo que contenga el archivo
    public function listar()
    {
        if (file_exists($this->archivo) && filesize($this->archivo) != 0) {
            try {
                $archivo = fopen($this->archivo, "r"); //abre el archivo en modo lectura
                return fread($archivo, filesize($this->archivo)); //lee hasta el final del archivo
            } catch (Exception $e) {
                throw new Exception("No se pudo listar", 0, $e);
            } finally {
                fclose($archivo); //cierra el archivo
            }
        } else {
            return "";
        }
    }

    //Trae un objeto sin JSON mediante una key y un valor que son recibidos por parametro
    public function getObjectByKeyCaseInsensitive($attrKey, $attrValue)
    {
        //Valido que el archivo este creado y que el size sea distinto de 0
        if (file_exists($this->archivo) && filesize($this->archivo) != 0) {
            try {
                $objects = json_decode($this->listar()); //Obtiene todos los objetos sin JSON
                foreach ($objects as $object) { //recorre cada uno de esos objetos
                    if (strtolower($object->$attrKey) == strtolower($attrValue)) { // pregunta si el objeto actual en la key recibida por parametro es igual al value recibido por parametro
                                                                                    //El $object->$attrKey devuelve su valor ejemplo ($object->nombre devuelve gabriel)
                        return $object; //si pasa el if de arriba retorna el objeto entero sin JSON
                    }
                }
                return null;
            } catch (Exception $e) {
                throw new Exception("No se pudo listar", 0, $e);
            }
        }
    }

    //Trae todos los objetos como JSON mediante una key y un valor que son recibidos por parametro
    public function getObjectsByKeyCaseInsensitive($attrKey, $attrValue)
    {
        //Valido que el archivo este creado y que el size sea distinto de 0
        if (file_exists($this->archivo) && filesize($this->archivo) != 0) {

            try {
                $objects = json_decode($this->listar());//Obtiene todos los objetos sin JSON
                $retorno = array(); //se crea un array vacio para el retorno de la funcion
                foreach ($objects as $object) {//recorre cada uno de esos objetos
                    //Comparo todo en minuscula
                    if (strtolower($object->$attrKey) == strtolower($attrValue)) {// pregunta si el objeto actual en la key recibida por parametro es igual al value recibido por parametro
                                                                                    //El $object->$attrKey devuelve su valor ejemplo ($object->nombre devuelve gabriel)
                        array_push($retorno, $object);//pushea al array a retornar todos los objetos que coincidan con la condicion del if de arriba
                    }
                }
                if (count($retorno) > 0) {
                    return json_encode($retorno); //si se pusheo algun objeto convierte el array en JSON y lo retorna
                } else {
                    return null;
                }
            } catch (Exception $e) {
                throw new Exception("No se pudo listar", 0, $e);
            }
        } else {
            //Si no esta creado retorno null
            return null;
        }
    }

    //Guarda un objeto, recibido por parametro en el archivo
    public function guardar($object): bool
    {
        try {
            $objects = [];
            if (file_exists($this->archivo) && filesize($this->archivo) != 0) {
                $jsonDecoded = json_decode($this->listar()); //Obtiene todos los objetos sin JSON
                //Valido si el array de json esta vacio
                if (count($jsonDecoded) > 0) {
                    //Si no está vacio, copio el contenido de json decode a $objects.
                    $objects = $jsonDecoded;
                }
            }
            //Abro el archivo en modo escritura
            $archivo = fopen($this->archivo, "w");
            //Pusheo mi objeto creado al array de objetos json
            array_push($objects, $object);
            //Codifico el array como json y escribo el archivo con ese array
            fwrite($archivo, json_encode($objects)); 
            return true;
        } catch (Exception $e) {
            throw new Exception("No se pudo guardar", 0, $e);
        } finally {
            fclose($archivo);
        }
    }

    //Borra un objeto del archivo mediante una key y value pasados por parametro
    public function borrar($idKey, $idValue): bool
    {
        try {
            $retorno = false;
            $objects = json_decode($this->listar()); //Obtiene todos los objetos sin JSON
            $archivo = fopen($this->archivo, "w"); //abre el archivo en modo escritura
            foreach ($objects as $key => $object) { //recorre cada uno de los objetos
                if ($object->$idKey == $idValue) { //pregunta si el objeto en la key recibida por parametro devuelve el mismo valor recibido por parametro
                    unset($objects[$key]); //borra de objects la key del objeto que coincide con el if de arriba
                    break;
                }
            }

            return fwrite($archivo, json_encode($objects)); // escribe el archivo con el nuevo array sin el objeto que se deseo borrar
        } catch (Exception $e) {
            throw new Exception("No se pudo borrar", 0, $e);
        } finally {
            fclose($archivo);
        }
    }

    //Modifica un objeto del archivo mediante una key, un value y un objeto con sus ya campos modificados
    public function modificar($idKey, $idValue, $objeto): bool
    {
        try {
            $objects = json_decode($this->listar()); //Obtiene todos los objetos sin JSON
            $rta = false;
            for ($i = 0; $i < count($objects); $i++) { //recorre cada uno de esos objetos
                if ($objects[$i]->$idKey == $idValue) { //pregunta si el objeto en la key recibida por parametro devuelve el mismo valor recibido por parametro
                    $objects[$i] = $objeto; //sobreescribe al objeto que estaba en esa posicion del array con el nuevo objeto recibido por parametro
                    $rta = true;
                    break;
                }
            }
            if($rta === true) //si se sobreescribio correctamente el objeto ya modificado en $objects
            {
                $archivo = fopen($this->archivo, "w"); // abro el archivo en modo escritura
                return fwrite($archivo, json_encode($objects)); //piso el archivo con mi nuevo array con JSON
            }
            else{
                return $rta;
            }
        } catch (Exception $e) {
            throw new Exception("No se pudo modificar", 0, $e);
        } finally {
            fclose($archivo);
        }
    }
}

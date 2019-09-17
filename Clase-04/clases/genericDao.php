<?php
    class GenericDao
    {
        //TODO meter try catch finally
        public $archivo;

        public function __construct($archivo)
        {
            $this->archivo = $archivo;
        }
        
        function guardar($object){
            $json = $this->listar();
            $archivo = fopen($this->archivo, "w");
            $objects = json_decode($json);
            array_push($objects, $object);
            fwrite($archivo, json_encode($objects));
            fclose($archivo);
        }

        function listar(){
            $archivo = fopen($this->archivo, "r");
            try {
                return fread($archivo, filesize($this->archivo));
            }
            catch (Exception $e) {
                throw $e;
            }
            finally {
                fclose($archivo);
            }
        }

        function borrar($idName, $idValue){
            $json = $this->listar();
            $archivo = fopen($this->archivo, "w");
            $objects = json_decode($json);
            foreach($objects as $key => $object){
                if($object->$idName == $idValue){
                    unset($objects[$key]);
                }
            }
            fwrite($archivo, json_encode($objects));
            fclose($archivo);
        }
    }
?>
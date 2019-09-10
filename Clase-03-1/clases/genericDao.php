<?php
    class GenericDao
    {
        public $archivo;

        public function __construct($archivo)
        {
            $this->archivo = $archivo;
        }
        
        function guardar($object){
            $archivo = fopen($this->archivo, "a");
            fwrite($archivo, PHP_EOL.$object->param1.' - '.$object->param2.' - '.$object->id);
            fclose($archivo);
        }

        function listar(){
            $objects = array();
            $archivo = fopen($this->archivo, "r");
            while(!feof($archivo)) {
                $aux = explode(" - ", fgets($archivo));

                if(count($aux) > 2) {
                    $object = new Object($aux[0], $aux[1], $aux[2]);
                    array_push($objects, $object);
                }
            }
            fclose($archivo);
            return json_encode($objects);
        }

    }

?>
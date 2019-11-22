<?php
class cdApi
{
    public function traerTodos($request, $response, $args){ 
        return $response->getbody()->write("Hello world GET");
    }
    
    public function traerUno($request, $response, $args){ 
        $nombre=$args['nombre'];
        return $response->getbody()->write("Hello <h1>$nombre</h1>");
    }

    public function cargarUno($request, $response, $args){ 
        return $response->getbody()->write("Hello world POST");
    }

    public function modificarUno($request, $response, $args){ 
        return $response->getbody()->write("Hello world PUT");
    }

    public function borrarUno($request, $response, $args){ 
        return $response->getbody()->write("Hello world DELETE");
    }


}
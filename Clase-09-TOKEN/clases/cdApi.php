<?php
class cdApi
{
    public function print($request, $response, $args){ 
        return $response->getbody()->write("Function response <br>");
    }
}
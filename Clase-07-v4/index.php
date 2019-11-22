<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './clases/alumno.php';
require_once './clases/genericDao.php';
require_once './clases/alumnoController.php';
require_once './clases/cdApi.php';


$config ['displayErrorDetails'] = true;
$config ['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

// $app->get('/hello', function ($request,  $response, $args) {
//     $response->getBody()->write("Hello, World");
//     return $response;

$app->group('/alumno', function() {
    $this->get('/', cdApi::class . ':traerTodos');

    $this->get('/{apellido}', cdApi::class . ':traerUno');

    $this->post('/', cdApi::class . ':cargarUno');
    
    $this->put('/', cdApi::class . ':modificarUno');
    
    $this->delete('/', cdApi::class . ':borrarUno');

});

$app -> run();



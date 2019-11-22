<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';

$config ['displayErrorDetails'] = true;
$config ['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

// $app->get('/hello', function ($request,  $response, $args) {
//     $response->getBody()->write("Hello, World");
//     return $response;

$app->group('/saludo', function() {
    $this->get('/', function ($request, $response, $args) {
        $response->getbody()->write("Hello world get");
    });

    $this->get('/{nombre}', function ($request, $response, $args) {
        $nombre=$args['nombre'];
        $response->getbody()->write("Hello <h1>$nombre</h1>");
    });

    $this->post('/', function ($request, $response, $args) {
        $response->getbody()->write("Hello world post");
    });

    $this->put('/', function ($request, $response, $args) {
        $response->getbody()->write("Hello world put");
    });

    $this->delete('/', function ($request, $response, $args) {
        $response->getbody()->write("Hello world delete");
    });


});

$app -> run();



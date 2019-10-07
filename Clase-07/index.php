<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once './vendor/autoload.php';
$config ['displayErrorDetails'] = true;
$config ['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);
$app->get('/hello', function ($request,  $response, $args) {
    $response->getBody()->write("Hello, World");
    return $response;
});
$app -> run();
<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './clases/log.php';
require_once './clases/genericDao.php';
require_once './clases/logController.php';
require_once './clases/autentificadorJWT.php';
require_once './clases/cdApi.php';

$config ['displayErrorDetails'] = true;
$config ['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$mwUno = function($request, $response, $next) {
    // $logController = new LogController();
    $autentificadorJWT = new AutentificadorJWT();
    $response->getBody()->write("Validando datos... <br>");
    // $logController->logear($request);
    $token = $request->getHeader("token")[0];
    $rsp = $autentificadorJWT->autentificar($token);
    if($rsp){
        var_dump($rsp);
        $response = $next($request, $response);
    }
    // $response->getBody()->write("Status 200 <br>");
    return $response;
};

$app->add($mwUno);

$app->group('/log', function() {
    
    $this->get('/', cdApi::class . ':print');

    // $this->post('/', cdApi::class . ':');
    
    // $this->put('/', cdApi::class . ':');
    
    // $this->delete('/', cdApi::class . ':');

});

$app -> run();



<?php

use App\Models\ORM\UsuarioController;
use Slim\App;

include_once __DIR__ . '/../../src/app/modelORM/usuario.php';
include_once __DIR__ . '/../../src/app/modelORM/usuarioController.php';

return function (App $app) {
    $container = $app->getContainer();
    $app->group('/usuario', function () {
        $this->get('[/]', UsuarioController::class . ':traerTodos');
        $this->post('[/]', UsuarioController::class . ':cargarUno');
        $this->post('/login[/]', UsuarioController::class . ':login');
    });
};

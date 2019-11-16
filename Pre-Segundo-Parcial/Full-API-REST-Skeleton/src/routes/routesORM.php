<?php

use App\Models\ORM\MateriaController;
use App\Models\ORM\UsuarioController;
use Slim\App;
//use Middleware;

include_once __DIR__ . '/../../src/app/modelORM/usuarioController.php';
include_once __DIR__ . '/../../src/app/modelORM/materiaController.php';
//include_once __DIR__ . '/../../src/middleware.php';

return function (App $app) {
    $container = $app->getContainer();
    $app->group('/usuario', function () {
        $this->get('[/]', UsuarioController::class . ':traerTodos');
        $this->post('/modificar[/]', UsuarioController::class . ':modificarUno')->add(Middleware::class . ':validarToken')->add(Middleware::class . ':obtenerTipo');
        $this->post('[/]', UsuarioController::class . ':cargarUno');
        $this->post('/login[/]', UsuarioController::class . ':login');
    });

    $app->group('/materia', function () {
        $this->post('[/]', MateriaController::class . ':cargarUno')->add(Middleware::class . ':validarToken')->add(Middleware::class . ':esAdmin');
    });
};
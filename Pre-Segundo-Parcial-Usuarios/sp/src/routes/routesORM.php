<?php

use App\Models\ORM\UserController;
use Slim\App;

include_once __DIR__ . '/../../src/app/modelORM/user.php';
include_once __DIR__ . '/../../src/app/modelORM/userController.php';

return function (App $app) {
    $container = $app->getContainer();

    $app->group('/users', function () {
        $this->post('[/]', UserController::class . ':cargarUno');
    });

    $app->group('/login', function () {
        $this->post('[/]', UserController::class . ':login');
    });

    $app->group('/ingreso', function () {
        $this->post('[/]', UserController::class . ':ficharIngreso')->add(Middleware::class . ':validarToken');
        $this->get('[/]', UserController::class . ':obtenerIngresos')->add(Middleware::class . ':validarToken');
    });

    $app->group('/egreso', function () {
        $this->post('[/]', UserController::class . ':ficharEgreso')->add(Middleware::class . ':validarToken');
    });

};

<?php

use App\Models\ORM\UserController;
use Slim\App;

include_once __DIR__ . '/../../src/app/modelORM/user.php';
include_once __DIR__ . '/../../src/app/modelORM/userController.php';

return function (App $app) {
    $container = $app->getContainer();

    $app->group('/users', function () {
        $this->post('[/]', UserController::class . ':cargarUno')
            ->add(Middleware::class . ":logeo");
    });

    $app->group('/login', function () {
        $this->post('[/]', UserController::class . ':login')
            ->add(Middleware::class . ":logeo");
    });

    $app->group('/ingreso', function () {
        $this->put('[/]', UserController::class . ':ficharIngreso')
            ->add(Middleware::class . ":logeo")
            ->add(Middleware::class . ':validarToken');

        $this->get('[/]', UserController::class . ':obtenerIngresos')
            ->add(Middleware::class . ":logeo")
            ->add(Middleware::class . ':validarToken');
    });

    $app->group('/egreso', function () {
        $this->put('[/]', UserController::class . ':ficharEgreso')
            ->add(Middleware::class . ":logeo")
            ->add(Middleware::class . ':validarToken');
    });

};

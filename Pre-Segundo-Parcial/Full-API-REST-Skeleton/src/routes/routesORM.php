<?php

use App\Models\ORM\MateriaController;
use App\Models\ORM\UsuarioController;
use Slim\App;

include_once __DIR__ . '/../../src/app/modelORM/usuarioController.php';
include_once __DIR__ . '/../../src/app/modelORM/materiaController.php';

return function (App $app) {
    $container = $app->getContainer();
    $app->group('/usuario', function () {
        $this->get('[/]', UsuarioController::class . ':traerTodos');
        //1
        $this->post('[/]', UsuarioController::class . ':cargarUno');
        //4
        $this->post('/{legajo}[/]', UsuarioController::class . ':modificarUno')->add(Middleware::class . ':validarToken')
            ->add(Middleware::class . ':obtenerTipo');
    });

    $app->group('/login', function () {
        //2
        $this->post('[/]', UsuarioController::class . ':login');
    });

    $app->group('/inscripcion', function () {
        //5
        $this->post('/{idmateria}[/]', MateriaController::class . ':inscripcionAlumno')->add(Middleware::class . ':validarToken')
            ->add(Middleware::class . ':esAlumno');
    });

    $app->group('/materia', function () {
        //3
        $this->post('[/]', MateriaController::class . ':cargarUno')->add(Middleware::class . ':validarToken')
            ->add(Middleware::class . ':esAdmin');
        //6
        $this->get('s[/]', MateriaController::class . ':traerTodos')->add(Middleware::class . ':validarToken')
            ->add(Middleware::class . ':obtenerTipoYId');
        //7
        $this->get('s/{idmateria}[/]', MateriaController::class . ':listaAlumnos')->add(Middleware::class . ':validarToken')
            ->add(Middleware::class . ':filtroListaAlumnos')->add(Middleware::class . ':obtenerTipoYId');
    });
};

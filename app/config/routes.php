<?php

use Slim\App;

// Definir as rotas
return function (App $app) {
    $app->get('/api/home', [\App\Controller\HomeController::class, 'home']);
    $app->get('/api/gerar/adm', [\App\Controller\UserController::class, 'gerarAdm']);
    $app->post('/api/login', [\App\Controller\UserController::class, 'login']);

    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $gApi) {
        $gApi->post('/pessoa', [\App\Controller\PessoaController::class, 'cadastrar']);
        $gApi->get('/cidade/select', [\App\Controller\CidadeController::class, 'select']);
    })
    ->add(\App\Middleware\AuthMiddleware::class);
};
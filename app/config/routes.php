<?php

use App\Controller\UserController;
use Slim\App;
use App\Controller\HomeController;

// Definir as rotas
return function (App $app) {
    $app->get('/api/home', [HomeController::class, 'home']);
    $app->get('/api/gerar/adm', [UserController::class, 'gerarAdm']);
    $app->post('/api/login', [UserController::class, 'login']);
};
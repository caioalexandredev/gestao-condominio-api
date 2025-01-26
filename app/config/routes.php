<?php

use Slim\App;
use App\Controller\HomeController;

// Definir as rotas
return function (App $app) {
    // Rota para a Home
    $app->get('/api/home', [HomeController::class, 'home']);

};
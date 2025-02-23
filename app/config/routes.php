<?php

use Slim\App;

return function (App $app) {
    $app->get('/api/home', [\App\Controller\HomeController::class, 'home']);
    $app->get('/api/gerar/adm', [\App\Controller\UserController::class, 'gerarAdm']);
    $app->post('/api/login', [\App\Controller\UserController::class, 'login']);

    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $gApi) {
        $gApi->post('/pessoa', [\App\Controller\PessoaController::class, 'cadastrar']);
        $gApi->get('/pessoa/listagem', [\App\Controller\PessoaController::class, 'listagem']);
        $gApi->get('/pessoa/{id}', [\App\Controller\PessoaController::class, 'consultar']);
        $gApi->delete('/pessoa/{id}', [\App\Controller\PessoaController::class, 'excluir']);
        $gApi->put('/pessoa/{id}', [\App\Controller\PessoaController::class, 'atualizar']);
        $gApi->get('/cidade/select', [\App\Controller\CidadeController::class, 'select']);
        $gApi->get('/estado/select', [\App\Controller\EstadoController::class, 'select']);
    })->add(\App\Middleware\AuthMiddleware::class);
};
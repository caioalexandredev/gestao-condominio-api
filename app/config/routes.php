<?php

use Slim\App;

return function (App $app) {
    $app->get('/api/home', [\App\Controller\HomeController::class, 'home']);
    $app->get('/api/gerar/adm', [\App\Controller\UserController::class, 'gerarAdm']);
    $app->post('/api/login', [\App\Controller\UserController::class, 'login']);

    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $gApi) {
        $gApi->post('/pessoa', [\App\Controller\PessoaController::class, 'cadastrar']);
        $gApi->get('/pessoa/listagem', [\App\Controller\PessoaController::class, 'listagem']);
        $gApi->get('/pessoa/select', [\App\Controller\PessoaController::class, 'select']);
        $gApi->get('/pessoa/{id}', [\App\Controller\PessoaController::class, 'consultar']);
        $gApi->delete('/pessoa/{id}', [\App\Controller\PessoaController::class, 'excluir']);
        $gApi->put('/pessoa/{id}', [\App\Controller\PessoaController::class, 'atualizar']);

        $gApi->get('/cidade/select', [\App\Controller\CidadeController::class, 'select']);
        $gApi->get('/estado/select', [\App\Controller\EstadoController::class, 'select']);

        $gApi->post('/propriedade', [\App\Controller\PropriedadeController::class, 'cadastrar']);
        $gApi->get('/propriedade/listagem', [\App\Controller\PropriedadeController::class, 'listagem']);
        $gApi->get('/propriedade/select', [\App\Controller\PropriedadeController::class, 'select']);
        $gApi->get('/propriedade/tipo/select', [\App\Controller\PropriedadeTipoController::class, 'select']);
        $gApi->get('/propriedade/{id}', [\App\Controller\PropriedadeController::class, 'consultar']);
        $gApi->delete('/propriedade/{id}', [\App\Controller\PropriedadeController::class, 'excluir']);
        $gApi->put('/propriedade/{id}', [\App\Controller\PropriedadeController::class, 'atualizar']);

        $gApi->post('/veiculo', [\App\Controller\VeiculoController::class, 'cadastrar']);
        $gApi->get('/veiculo/marca/select', [\App\Controller\VeiculoMarcaController::class, 'select']);
        $gApi->get('/veiculo/cor/select', [\App\Controller\VeiculoCorController::class, 'select']);
        $gApi->get('/veiculo/categoria/select', [\App\Controller\VeiculoCategoriaController::class, 'select']);
        $gApi->get('/veiculo/listagem', [\App\Controller\VeiculoController::class, 'listagem']);
        $gApi->get('/veiculo/{id}', [\App\Controller\VeiculoController::class, 'consultar']);
        $gApi->delete('/veiculo/{id}', [\App\Controller\VeiculoController::class, 'excluir']);
        $gApi->put('/veiculo/{id}', [\App\Controller\VeiculoController::class, 'atualizar']);

        $gApi->get('/conta/pagar/categoria/select', [\App\Controller\ContaPagarCategoriaController::class, 'select']);
        $gApi->get('/conta/receber/categoria/select', [\App\Controller\ContaReceberCategoriaController::class, 'select']);
        $gApi->get('/conta/status/select', [\App\Controller\ContaStatusController::class, 'select']);
        $gApi->get('/conta/tipo/select', [\App\Controller\ContaTipoController::class, 'select']);
        $gApi->get('/informativo/visibilidade/select', [\App\Controller\InformativoVisibilidadeController::class, 'select']);
        $gApi->get('/ocorrencia/tipo/select', [\App\Controller\OcorrenciaTipoController::class, 'select']);

        $gApi->post('/conta/pagar', [\App\Controller\ContaPagarController::class, 'cadastrar']);
        $gApi->get('/conta/pagar/listagem', [\App\Controller\ContaPagarController::class, 'listagem']);
        $gApi->get('/conta/pagar/{id}', [\App\Controller\ContaPagarController::class, 'consultar']);
        $gApi->delete('/conta/pagar/{id}', [\App\Controller\ContaPagarController::class, 'excluir']);
        $gApi->put('/conta/pagar/{id}', [\App\Controller\ContaPagarController::class, 'atualizar']);

        $gApi->post('/conta/receber', [\App\Controller\ContaReceberController::class, 'cadastrar']);
        $gApi->get('/conta/receber/listagem', [\App\Controller\ContaReceberController::class, 'listagem']);
        $gApi->get('/conta/receber/{id}', [\App\Controller\ContaReceberController::class, 'consultar']);
        $gApi->delete('/conta/receber/{id}', [\App\Controller\ContaReceberController::class, 'excluir']);
        $gApi->put('/conta/receber/{id}', [\App\Controller\ContaReceberController::class, 'atualizar']);

        $gApi->post('/informativo', [\App\Controller\InformativoController::class, 'cadastrar']);
        $gApi->get('/informativo/listagem', [\App\Controller\InformativoController::class, 'listagem']);
        $gApi->get('/informativo/{id}', [\App\Controller\InformativoController::class, 'consultar']);
        $gApi->delete('/informativo/{id}', [\App\Controller\InformativoController::class, 'excluir']);
        $gApi->put('/informativo/{id}', [\App\Controller\InformativoController::class, 'atualizar']);

        $gApi->post('/ocorrencia', [\App\Controller\OcorrenciaController::class, 'cadastrar']);
        $gApi->get('/ocorrencia/listagem', [\App\Controller\OcorrenciaController::class, 'listagem']);
        $gApi->get('/ocorrencia/{id}', [\App\Controller\OcorrenciaController::class, 'consultar']);
        $gApi->delete('/ocorrencia/{id}', [\App\Controller\OcorrenciaController::class, 'excluir']);
        $gApi->put('/ocorrencia/{id}', [\App\Controller\OcorrenciaController::class, 'atualizar']);
    })->add(\App\Middleware\AuthMiddleware::class);
};
<?php

namespace App\Controller;

use App\Service\VeiculoCategoriaService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class VeiculoCategoriaController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private VeiculoCategoriaService $veiculoCategoriaService,
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Get(
        path: '/api/veiculo/categoria/select',
        summary: 'Select de categorias de veículo',
        tags: ['Veículo'],
        security: [['api_key' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function select(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            return $this->jsonResponse($response, $session, $this->veiculoCategoriaService->select());
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
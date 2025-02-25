<?php

namespace App\Controller;

use App\Service\ContaReceberCategoriaService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ContaReceberCategoriaController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private ContaReceberCategoriaService $contaReceberCategoriaService,
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Get(
        path: '/api/conta/receber/categoria/select',
        summary: 'Select de categorias de conta a receber',
        tags: ['Conta a Pagar'],
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
            return $this->jsonResponse($response, $session, $this->contaReceberCategoriaService->select());
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
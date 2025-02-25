<?php

namespace App\Controller;

use App\Service\ContaPagarCategoriaService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ContaPagarCategoriaController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private ContaPagarCategoriaService $contaPagarCategoriaService,
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Get(
        path: '/api/conta/pagar/categoria/select',
        summary: 'Select de categorias de conta a pagar',
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
            return $this->jsonResponse($response, $session, $this->contaPagarCategoriaService->select());
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
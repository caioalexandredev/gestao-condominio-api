<?php

namespace App\Controller;

use App\Service\ContaTipoService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ContaTipoController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private ContaTipoService $contaTipoService,
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Get(
        path: '/api/conta/tipo/select',
        summary: 'Select de tipo de conta',
        tags: ['Financeiro'],
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
            return $this->jsonResponse($response, $session, $this->contaTipoService->select());
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
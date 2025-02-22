<?php

namespace App\Controller;

use App\Service\CidadeService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class CidadeController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private CidadeService $cidadeService,
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Get(
        path: '/api/cidade/select',
        summary: 'Select de cidades com base na UF',
        tags: ['Cidade'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'uf',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string'),
                description: 'UF'
            )
        ],
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
            $data = $this->getDataRequest($request);
            $result = $this->cidadeService->select($data['uf']);

            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
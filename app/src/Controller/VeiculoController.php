<?php

namespace App\Controller;

use App\Service\VeiculoService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class VeiculoController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private VeiculoService $veiculoService
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Post(
        path: '/api/veiculo',
        summary: 'Realiza o cadastro completo de veículo',
        tags: ['Veículo'],
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'categoria', type: 'integer'),
                        new OA\Property(property: 'cor', type: 'integer'),
                        new OA\Property(property: 'marca', type: 'integer'),
                        new OA\Property(property: 'proprietario', type: 'integer'),
                        new OA\Property(property: 'propriedade', type: 'integer'),
                        new OA\Property(property: 'modelo', type: 'string'),
                        new OA\Property(property: 'placa', type: 'string'),
                        new OA\Property(property: 'ano', type: 'string')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function cadastrar(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);
            $this->veiculoService->cadastrar($data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/veiculo/listagem',
        summary: 'Listagem de Veículo do Sistema',
        tags: ['Veículo'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'nome',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Nome'
            ),
            new OA\Parameter(
                name: 'placa',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Placa'
            ),
            new OA\Parameter(
                name: 'modelo',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer'),
                description: 'Modelo'
            ),
            new OA\Parameter(
                name: 'categoria',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Categoria'
            ),
            new OA\Parameter(
                name: 'pagina',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Página'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function listagem(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);

            $result = $this->veiculoService->listagem(
                $data['nome'] ?? null,
                $data['categoria'] ?? null,
                $data['placa'] ?? null,
                $data['modelo'] ?? null,
                $data['pagina'] ?? null
            );
            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/veiculo/{id}',
        summary: 'Consultar Veículo',
        tags: ['Veículo'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'id'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function consultar(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);
            $data['id'] = $this->getAttributeRequest($request, 'id');
            return $this->jsonResponse($response, $session, $this->veiculoService->consultarDados($data['id']));
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Delete(
        path: '/api/veiculo/{id}',
        summary: 'Excluir Veículo do Sistema',
        tags: ['Veículo'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Id'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function excluir(
        ResponseInterface      $response,
        RequestInterface       $request,
        SessionInterface       $session,
    ): ResponseInterface
    {
        try{
            $data = $this->getDataRequest($request);
            $data['id'] = $this->getAttributeRequest($request, 'id');

            $this->veiculoService->deletar($data['id']);

            return $this->jsonResponse($response, $session, true);
        }catch(Throwable $e){
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Put(
        path: '/api/veiculo/{id}',
        summary: 'Atualiza o cadastro completo de veículo',
        tags: ['Veículo'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Id'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'categoria', type: 'integer'),
                        new OA\Property(property: 'cor', type: 'integer'),
                        new OA\Property(property: 'marca', type: 'integer'),
                        new OA\Property(property: 'proprietario', type: 'integer'),
                        new OA\Property(property: 'propriedade', type: 'integer'),
                        new OA\Property(property: 'modelo', type: 'string'),
                        new OA\Property(property: 'placa', type: 'string'),
                        new OA\Property(property: 'ano', type: 'string')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function atualizar(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);
            $data['id'] = $this->getAttributeRequest($request, 'id');
            
            $this->veiculoService->atualizar($data['id'], $data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
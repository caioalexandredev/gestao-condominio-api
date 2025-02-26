<?php

namespace App\Controller;

use App\Service\ContaReceberService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ContaReceberController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private ContaReceberService $contaReceberService
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Post(
        path: '/api/conta/receber',
        summary: 'Realiza o cadastro completo de uma conta a receber',
        tags: ['Contas a Receber'],
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'tipo', type: 'integer', description: 'Tipo de Conta'),
                        new OA\Property(property: 'status', type: 'integer', description: 'Status da Conta'),
                        new OA\Property(property: 'propriedade', type: 'integer', description: 'Propriedade'),
                        new OA\Property(property: 'proprietario', type: 'string', description: 'Proprietário'),
                        new OA\Property(property: 'categoria', type: 'integer', description: 'Categoria da Conta'),
                        new OA\Property(property: 'descricao', type: 'string', description: 'Descrição da Conta'),
                        new OA\Property(property: 'valor', type: 'float', description: 'Valor da Conta'),
                        new OA\Property(property: 'vencimento', type: 'string', description: 'Vencimento da Conta'),
                        new OA\Property(property: 'observacao', type: 'string', description: 'Observação'),
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
            $this->contaReceberService->cadastrar($data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/conta/receber/listagem',
        summary: 'Listagem de Contas a Receber do Sistema',
        tags: ['Contas a Receber'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'descricao',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Descrição da Conta'
            ),
            new OA\Parameter(
                name: 'tipo',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Tipo de Conta'
            ),
            new OA\Parameter(
                name: 'dt_inicio_vencimento',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer'),
                description: 'Data de Vencimento | Início'
            ),
            new OA\Parameter(
                name: 'dt_fim_vencimento',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Data de Vencimento | Fim'
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

            $result = $this->contaReceberService->listagem(
                $data['descricao'] ?? null,
                $data['tipo'] ?? null,
                $data['dt_inicio_vencimento'] ?? null,
                $data['dt_fim_vencimento'] ?? null,
                $data['pagina'] ?? null
            );
            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/conta/receber/{id}',
        summary: 'Consultar Conta a Receber',
        tags: ['Contas a Receber'],
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
            return $this->jsonResponse($response, $session, $this->contaReceberService->consultarDados($data['id']));
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Delete(
        path: '/api/conta/receber/{id}',
        summary: 'Excluir Conta a Receber do Sistema',
        tags: ['Contas a Receber'],
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

            $this->contaReceberService->deletar($data['id']);

            return $this->jsonResponse($response, $session, true);
        }catch(Throwable $e){
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Put(
        path: '/api/conta/receber/{id}',
        summary: 'Atualiza o cadastro completo de conta a receber',
        tags: ['Contas a Receber'],
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
                        new OA\Property(property: 'tipo', type: 'integer', description: 'Tipo de Conta'),
                        new OA\Property(property: 'status', type: 'integer', description: 'Status da Conta'),
                        new OA\Property(property: 'categoria', type: 'integer', description: 'Categoria da Conta'),
                        new OA\Property(property: 'descricao', type: 'string', description: 'Descrição da Conta'),
                        new OA\Property(property: 'valor', type: 'float', description: 'Valor da Conta'),
                        new OA\Property(property: 'propriedade', type: 'integer', description: 'Propriedade'),
                        new OA\Property(property: 'proprietario', type: 'string', description: 'Proprietário'),
                        new OA\Property(property: 'vencimento', type: 'string', description: 'Vencimento da Conta'),
                        new OA\Property(property: 'observacao', type: 'string', description: 'Observação'),
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
            
            $this->contaReceberService->atualizar($data['id'], $data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
<?php

namespace App\Controller;

use App\Service\PropriedadeService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class PropriedadeController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private PropriedadeService $propriedadeService
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Post(
        path: '/api/propriedade',
        summary: 'Realiza o cadastro completo de propriedade',
        tags: ['Propriedade'],
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'tipo', type: 'integer', description: 'Nome do usuário'),
                        new OA\Property(property: 'observacao', type: 'string', description: 'Sobrenome do usuário'),
                        new OA\Property(property: 'proprietario', type: 'integer', description: 'Data de nascimento do usuário (YYYY-MM-DD)'),
                        new OA\Property(property: 'cep', type: 'string', description: 'CEP do endereço do usuário'),
                        new OA\Property(property: 'cidade', type: 'integer', description: 'Cidade do endereço do usuário'),
                        new OA\Property(property: 'logradouro', type: 'string', description: 'Nome da rua do endereço do usuário'),
                        new OA\Property(property: 'bairro', type: 'string', description: 'Bairro do endereço do usuário'),
                        new OA\Property(property: 'numero', type: 'string', description: 'Número do endereço do usuário'),
                        new OA\Property(property: 'complemento', type: 'string', description: 'Complemento do endereço (opcional)')
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
            $this->propriedadeService->cadastrar($data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/propriedade/listagem',
        summary: 'Listagem de Propriedade do Sistema',
        tags: ['Propriedade'],
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
                name: 'cpf',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'CPF'
            ),
            new OA\Parameter(
                name: 'tipo',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer'),
                description: 'Tipo'
            ),
            new OA\Parameter(
                name: 'endereco',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Endereco'
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

            $result = $this->propriedadeService->listagem(
                $data['nome'] ?? null,
                $data['cpf'] ?? null,
                $data['endereco'] ?? null,
                $data['tipo'] ?? null,
                $data['pagina'] ?? null
            );
            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/propriedade/{id}',
        summary: 'Consultar Propriedade',
        tags: ['Propriedade'],
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
            return $this->jsonResponse($response, $session, $this->propriedadeService->consultarDados($data['id']));
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Delete(
        path: '/api/propriedade/{id}',
        summary: 'Excluir Propriedade do Sistema',
        tags: ['Propriedade'],
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

            $this->propriedadeService->deletar($data['id']);

            return $this->jsonResponse($response, $session, true);
        }catch(Throwable $e){
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Put(
        path: '/api/propriedade/{id}',
        summary: 'Atualiza o cadastro completo de propriedade',
        tags: ['Propriedade'],
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
                        new OA\Property(property: 'tipo', type: 'integer', description: 'Nome do usuário'),
                        new OA\Property(property: 'observacao', type: 'string', description: 'Sobrenome do usuário'),
                        new OA\Property(property: 'proprietario', type: 'integer', description: 'Data de nascimento do usuário (YYYY-MM-DD)'),
                        new OA\Property(property: 'cep', type: 'string', description: 'CEP do endereço do usuário'),
                        new OA\Property(property: 'cidade', type: 'integer', description: 'Cidade do endereço do usuário'),
                        new OA\Property(property: 'logradouro', type: 'string', description: 'Nome da rua do endereço do usuário'),
                        new OA\Property(property: 'bairro', type: 'string', description: 'Bairro do endereço do usuário'),
                        new OA\Property(property: 'numero', type: 'string', description: 'Número do endereço do usuário'),
                        new OA\Property(property: 'complemento', type: 'string', description: 'Complemento do endereço (opcional)')
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
            
            $this->propriedadeService->atualizar($data['id'], $data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
<?php

namespace App\Controller;

use App\Service\OcorrenciaService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class OcorrenciaController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private OcorrenciaService $ocorrenciaService
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Post(
        path: '/api/ocorrencia',
        summary: 'Realiza o cadastro completo de uma ocorrência',
        tags: ['Ocorrência'],
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'tipo', type: 'integer', description: 'Tipo de Ocorrência'),
                        new OA\Property(property: 'assunto', type: 'integer', description: 'Assunto'),
                        new OA\Property(property: 'informacao', type: 'integer', description: 'Informação'),
                        new OA\Property(property: 'dt_ocorrencia', type: 'string', description: 'Data da Ocorrência'),
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
            $this->ocorrenciaService->cadastrar($data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/ocorrencia/listagem',
        summary: 'Listagem de Ocorrências do Sistema',
        tags: ['Ocorrência'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'assunto',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Assunto'
            ),
            new OA\Parameter(
                name: 'solicitante',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Solicitante'
            ),
            new OA\Parameter(
                name: 'dt_inicio_ocorrencia',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer'),
                description: 'Data de Ocorrência | Início'
            ),
            new OA\Parameter(
                name: 'dt_fim_ocorrencia',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Data de Ocorrência | Fim'
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

            $result = $this->ocorrenciaService->listagem(
                $data['assunto'] ?? null,
                $data['solicitante'] ?? null,
                $data['dt_inicio_ocorrencia'] ?? null,
                $data['dt_fim_ocorrencia'] ?? null,
                $data['pagina'] ?? null
            );
            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/ocorrencia/{id}',
        summary: 'Consultar Ocorrência',
        tags: ['Ocorrência'],
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
            return $this->jsonResponse($response, $session, $this->ocorrenciaService->consultarDados($data['id']));
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Delete(
        path: '/api/ocorrencia/{id}',
        summary: 'Excluir Ocorrência do Sistema',
        tags: ['Ocorrência'],
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

            $this->ocorrenciaService->deletar($data['id']);

            return $this->jsonResponse($response, $session, true);
        }catch(Throwable $e){
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Put(
        path: '/api/ocorrencia/{id}',
        summary: 'Atualiza o cadastro completo de ocorrência',
        tags: ['Ocorrência'],
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
                        new OA\Property(property: 'tipo', type: 'integer', description: 'Tipo de Ocorrência'),
                        new OA\Property(property: 'assunto', type: 'integer', description: 'Assunto'),
                        new OA\Property(property: 'informacao', type: 'integer', description: 'Informação'),
                        new OA\Property(property: 'dt_ocorrencia', type: 'string', description: 'Data da Ocorrência'),
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
            
            $this->ocorrenciaService->atualizar($data['id'], $data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
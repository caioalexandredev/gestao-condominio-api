<?php

namespace App\Controller;

use App\Service\LoginService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class UserController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private LoginService $loginService,
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\SecurityScheme(
        securityScheme: 'api_key',
        type: 'apiKey',
        in: 'header',
        name: 'Authorization'
    )]

    #[OA\Post(
        path: '/api/login',
        summary: 'Realiza login e obtem token de autenticação para app',
        tags: ['Usuário'],
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'cpf', type: 'string', description: 'CPF do usuário para autenticação'),
                        new OA\Property(property: 'senha', type: 'string', description: 'Senha do usuário para autenticação')
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
    public function login(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);
            
            $result = [
                'key' => $this->loginService->gerarTokenUsuario($data['cpf'], $data['senha'])
            ];

            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/gerar/adm',
        summary: 'Realiza geração de usuário administrador',
        tags: ['Usuário'],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function gerarAdm(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $result = [
                'usuarioGerado' => $this->loginService->gerarUsuarioADM()
            ];

            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}
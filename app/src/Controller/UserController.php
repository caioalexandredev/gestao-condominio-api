<?php

namespace App\Controller;

use App\Service\LoginService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class UserController extends DefaultController
{
    public function __construct(
        private LoginService $loginService,
    )
    {}

    #[OA\Get(
        path: '/api/login',
        summary: 'Realiza login e obtem token de autenticação para app',
        tags: ['Usuário'],
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
}
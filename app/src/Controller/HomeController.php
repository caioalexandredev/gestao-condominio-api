<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use OpenApi\Attributes as OA;

#[OA\Info(title: "Gestor de Condomínios", version: "0.1")]
class HomeController extends DefaultController
{
    public function __construct(
        private EntityManager $entityManager,
        private ContainerInterface $container
    ) {
        parent::__construct($container);
    }
    

    #[OA\Get(
        path: '/api/home',
        summary: 'Obter informações da página inicial do Dashboard',
        tags: ['Dashboard'],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function home(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $result = array('name' => 'Bob', 'age' => 40);

            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}

<?php

namespace App\Middleware;

use App\Service\LoginService;
use Exception;
use Monolog\Logger;
use \Odan\Session\SessionInterface;
use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private SessionInterface         $session,
        private LoginService $loginService,
        private Logger $log
    )
    {
    }

    public function __invoke(
        ServerRequestInterface  $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface
    {
        $token = preg_replace('/\s+/', '', str_replace('Bearer ', '', $request->getHeaderLine('Authorization')));
        
        try{
            $usuario = $this->logar($token);

            $this->session->destroy();
            $this->session->start();
            $this->session->regenerateId();
            $this->session->set('user', $usuario->getId());
            $this->session->save();
            
            return $handler->handle($request);

        }catch(Exception $e){
            $jsonResponse = json_encode(array('status' => 'error', 'erro' => $e->getMessage(), 'data' => []), JSON_UNESCAPED_UNICODE);
            $response = $this->responseFactory->createResponse(401);
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write($jsonResponse);
            $this->log->error($e->getMessage());
            return $response;
        }
    }

    private function logar($token) {
        if (empty($token)) {
            throw new Exception('Token de autenticação não fornecido');
        }

        $sub = $this->loginService->verificarTokenUsuario($token)['sub'];
        $usuario = $this->loginService->consultarUsuarioPorSub($sub);
    
        return $usuario;
    }
}
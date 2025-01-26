<?php

namespace App\Controller;

use App\Exception\BadRequestException;
use App\Exception\PermissaoException;
use Monolog\Logger;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

abstract class DefaultController
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
    const HTTP_STATUS_BAD_REQUEST = 400;
    const HTTP_STATUS_UNAUTHORIZED = 401;

    private Logger $log;
    
    public function __construct(
        private ContainerInterface $container,
    )
    {
        $this->log = $container->get(Logger::class);
    }

    protected function getAttributeRequest(RequestInterface $request, string $param){
        return $request->getAttribute($param);
    }

    protected function getFilesRequest(RequestInterface $request){
        return $request->getUploadedFiles();
    }

    protected function getDataRequest(RequestInterface $request){
        $data = array_merge($request->getQueryParams() ?? [], $request->getParsedBody() ?? []);
        $dataJson = json_decode($request->getBody());
        
        if(!$data && $dataJson){
            $data = get_object_vars($dataJson) ?? [];
        }

        return $data;
    }

    protected function jsonResponse(ResponseInterface $response, SessionInterface $session, $data, $httpStatus = 200, $status = self::STATUS_SUCCESS, $erro = '')  {
        $jsonResponse = json_encode(['status' => $status, 'erro' => $erro, 'data' => $data], JSON_UNESCAPED_UNICODE);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write($jsonResponse);
        
        $this->destroyAndSaveSession($session);

        return $response->withStatus($httpStatus);
    }

    protected function handleException(ResponseInterface $response, Throwable $e, SessionInterface $session): ResponseInterface {
        $httpStatus = self::HTTP_STATUS_INTERNAL_SERVER_ERROR;
        $errorMessage = $e instanceof BadRequestException ? ($e->getMessages() ?? $e->getMessage()) : $e->getMessage();
    
        if ($e instanceof BadRequestException) {
            $this->log->error(json_encode($errorMessage), $e->getTrace());
            $httpStatus = self::HTTP_STATUS_BAD_REQUEST;
        } elseif ($e instanceof PermissaoException) {
            $httpStatus = self::HTTP_STATUS_UNAUTHORIZED;
        }
    
        if($httpStatus == self::HTTP_STATUS_INTERNAL_SERVER_ERROR){
            $this->log->error(json_encode($errorMessage), $e->getTrace());
            $errorMessage = 'Erro interno de servidor';
        }
        
        return $this->jsonResponse($response, $session, '', $httpStatus, self::STATUS_ERROR, $errorMessage);
    }

    private function destroyAndSaveSession(SessionInterface $session) {
        $session->destroy();
        $session->save();
    }
}
<?php

namespace App\Handler;

use App\Controller\DefaultController;
use Monolog\Logger;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class DefaultErrorHandler extends DefaultController
{

    public function __construct(private ResponseFactoryInterface $responseFactory,
                                private Logger $log,
                                private ContainerInterface $container,
                                private SessionInterface $session
    )
    {
        parent::__construct($container);
    }

    public function __invoke(ServerRequestInterface $request,
                             Throwable              $exception,
                             bool                   $displayErrorDetails,
                             bool                   $logErrors,
                             bool                   $logErrorDetails)
    {
        $response = $this->responseFactory->createResponse();
        return $this->handleException($response, $exception, $this->session);
    }

}
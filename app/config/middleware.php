<?php

use App\Handler\DefaultErrorHandler;
use Odan\Session\Middleware\SessionStartMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;

return function (App $app, ContainerInterface $c) {
    $app->addBodyParsingMiddleware();
    $app->add(SessionStartMiddleware::class);
    $app->addRoutingMiddleware();

    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    $errorMiddleware->setErrorHandler(Throwable::class,
        DefaultErrorHandler::class, true);
};
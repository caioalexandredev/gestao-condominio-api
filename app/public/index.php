<?php

use Slim\App;

require __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

$container = (new \App\ContainerFactory())->createInstance();

return $container->get(App::class)->run();
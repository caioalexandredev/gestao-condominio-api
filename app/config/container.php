<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Odan\Session\PhpSession;
use Slim\App;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    App::class => function (ContainerInterface $c) {
        $app = \DI\Bridge\Slim\Bridge::create($c);
    
        // Adicionar middlewares
        (require __DIR__ . '/middleware.php')($app, $c);
        (require __DIR__ . '/routes.php')($app);
        
        return $app;
    },

    SessionManagerInterface::class => function (ContainerInterface $container) {
        return $container->get(SessionInterface::class);
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(\Slim\Psr7\Factory\ResponseFactory::class);
    },

    Logger::class => function () {
        $log = new Logger('app_logger');
    
        $log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));
    
        return $log;
    },

    SessionInterface::class => function () {
        $session = new PhpSession([
            'name' => getenv('SESSION_NAME'),
            'cookie_domain' => getenv('SESSION_COOKIE_DOMAIN'),
            'cookie_httponly' => getenv('SESSION_COOKIE_HTTPONLY'),
            'cookie_samesite' => getenv('SESSION_COOKIE_SAMESITE'),
            'cookie_secure' => getenv('SESSION_COOKIE_SECURE'),
            'gc_probability' => getenv('SESSION_GC_PROBABILITY'),
            'save_handler' => getenv('SESSION_SAVE_HANDLER'),
            'save_path' => getenv('SESSION_SAVE_PATH'),
            'serialize_handler' => getenv('SESSION_SERIALIZE_HANDLER'),
            'sid_bits_per_character' => getenv('SESSION_SID_BITS_PER_CHARACTER'),
            'sid_length' => getenv('SESSION_SID_LENGTH'),
            'use_only_cookies' => getenv('SESSION_USE_ONLY_COOKIES'),
            'use_strict_mode' => getenv('SESSION_USE_STRICT_MODE'),
            'use_trans_sid' => getenv('SESSION_USE_TRANS_SID'),
        ]);
    
        return $session;
    },

    EntityManager::class => function () 
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../src/Entity/.'],
            isDevMode: true,
        );
    
        // Configuração para auto geração de proxies
        $config->setAutoGenerateProxyClasses(2);

        $connection = DriverManager::getConnection([
            'driver'   => 'pdo_mysql',
            'user'     => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname'   => getenv('DB_NAME'),
            'host'     => getenv('DB_HOST'),
            'port'     => 3306,
        ], $config);
    
        // Criação do EntityManager
        return new EntityManager($connection, $config);
    }
];
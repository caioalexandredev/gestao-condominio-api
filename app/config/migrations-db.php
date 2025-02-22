<?php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../src/Entity/.'],
    isDevMode: true,
);

// Configuração para auto geração de proxies
$config->setAutoGenerateProxyClasses(true);

return DriverManager::getConnection([
    'driver'   => 'pdo_mysql',
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'dbname'   => getenv('DB_NAME'),
    'host'     => getenv('DB_HOST'),
    'port'     => 3306,
], $config);
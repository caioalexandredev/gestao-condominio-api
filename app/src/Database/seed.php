<?php

use App\Database\Fixtures\AdministradorFixture;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use App\Database\Fixtures\EstadoCidadeApiFixture;
use App\Database\Fixtures\PropriedadeTipoFixture;
use Doctrine\ORM\EntityManager;

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../");
$dotenv->load();

$container = (new \App\ContainerFactory())->createInstance();
$entityManager = $container->get(EntityManager::class);

$loader = new Loader();
$loader->addFixture(new AdministradorFixture());
$loader->addFixture(new EstadoCidadeApiFixture());
$loader->addFixture(new PropriedadeTipoFixture());

$purger = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures());

echo "Seed inserido com sucesso!\n";

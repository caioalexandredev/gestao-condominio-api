<?php

use App\Database\Fixtures\AdministradorFixture;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use App\Database\Fixtures\EstadoCidadeApiFixture;
use App\Database\Fixtures\PropriedadeTipoFixture;
use App\Database\Fixtures\VeiculoCategoriaFixture;
use App\Database\Fixtures\VeiculoCorFixture;
use App\Database\Fixtures\VeiculoMarcaFixture;
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
$loader->addFixture(new VeiculoCorFixture());
$loader->addFixture(new VeiculoMarcaFixture());
$loader->addFixture(new VeiculoCategoriaFixture());

$purger = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures());

echo "Seed inserido com sucesso!\n";

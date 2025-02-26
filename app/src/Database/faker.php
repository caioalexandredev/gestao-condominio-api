<?php

use App\Database\Fixtures\ContaPagarFixture;
use App\Database\Fixtures\ContaReceberFixture;
use App\Database\Fixtures\InformativoFixture;
use App\Database\Fixtures\OcorrenciaFixture;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use App\Database\Fixtures\PessoaDadosFixture;
use App\Database\Fixtures\PropriedadeFixture;
use App\Database\Fixtures\VeiculoFixture;
use Doctrine\ORM\EntityManager;

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../");
$dotenv->load();

$container = (new \App\ContainerFactory())->createInstance();
$entityManager = $container->get(EntityManager::class);

$loader = new Loader();
$loader->addFixture(new PessoaDadosFixture());
$loader->addFixture(new PropriedadeFixture());
$loader->addFixture(new VeiculoFixture());
$loader->addFixture(new ContaPagarFixture());
$loader->addFixture(new ContaReceberFixture());
$loader->addFixture(new InformativoFixture());
$loader->addFixture(new OcorrenciaFixture());

$purger = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures(), true);

echo "\033[32mFakers inseridos com sucesso!\033[0m\n";

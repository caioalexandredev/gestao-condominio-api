<?php

namespace App;

use Psr\Container\ContainerInterface;

class ContainerFactory
{

    public function __construct()
    {

    }

    public function createInstance(): ContainerInterface {
        // Create Container
        $builder = new \DI\ContainerBuilder();

        $builder->addDefinitions(__DIR__ . '/../config/container.php');
        return $builder->build();
    }
}
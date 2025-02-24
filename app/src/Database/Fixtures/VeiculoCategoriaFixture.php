<?php

namespace App\Database\Fixtures;

use App\Entity\VeiculoCategoria;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class VeiculoCategoriaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $cores = [
            1 => 'Carro',
            2 => 'Moto',
            3 => 'Caminhão'
        ];

        foreach ($cores as $id => $descricao) {
            $this->cadastrar($manager, $id, $descricao);
        }

        $manager->flush();

        echo "Categorias de veículos inseridas com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $veiculoCor = new VeiculoCategoria();
        $veiculoCor->setId($id);
        $veiculoCor->setDescricao($descricao);

        $manager->persist($veiculoCor);
    }
}

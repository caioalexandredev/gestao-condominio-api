<?php

namespace App\Database\Fixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\VeiculoCor;
use Doctrine\Common\DataFixtures\AbstractFixture;

class VeiculoCorFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $cores = [
            1 => 'Preto',
            2 => 'Branco',
            3 => 'Cinza',
            4 => 'Prata',
            5 => 'Vermelho',
            6 => 'Azul',
            7 => 'Verde',
            8 => 'Amarelo',
            9 => 'Marrom',
            10 => 'Laranja'
        ];

        foreach ($cores as $id => $descricao) {
            $this->cadastrar($manager, $id, $descricao);
        }

        $manager->flush();

        echo "Cores de veículos inseridas com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $veiculoCor = new VeiculoCor();
        $veiculoCor->setId($id);
        $veiculoCor->setDescricao($descricao);

        $manager->persist($veiculoCor);
    }
}

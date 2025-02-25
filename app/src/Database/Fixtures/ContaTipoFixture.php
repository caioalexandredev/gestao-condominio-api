<?php

namespace App\Database\Fixtures;

use App\Entity\ContaTipo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class ContaTipoFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->cadastrar($manager, 1, "Fixo");
        $this->cadastrar($manager, 2, "Variado");

        $manager->flush();

        echo "Tipos de Conta inseridos com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $estado = new ContaTipo();
        $estado->setId($id);
        $estado->setDescricao($descricao);

        $manager->persist($estado);
    }
}

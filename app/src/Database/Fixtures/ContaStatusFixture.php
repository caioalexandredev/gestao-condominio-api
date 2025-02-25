<?php

namespace App\Database\Fixtures;

use App\Entity\ContaStatus;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class ContaStatusFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->cadastrar($manager, 1, "Pendente");
        $this->cadastrar($manager, 2, "Pago");
        $this->cadastrar($manager, 3, "Atrasado");

        $manager->flush();

        echo "Tipos de Status de Conta inseridos com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $estado = new ContaStatus();
        $estado->setId($id);
        $estado->setDescricao($descricao);

        $manager->persist($estado);
    }
}

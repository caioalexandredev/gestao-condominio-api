<?php

namespace App\Database\Fixtures;

use App\Entity\ContaPagarCategoria;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class ContaPagarCategoriaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->cadastrar($manager, 1, "Água");
        $this->cadastrar($manager, 2, "Energia");
        $this->cadastrar($manager, 3, "Manutenção");
        $this->cadastrar($manager, 4, "Serviço");

        $manager->flush();

        echo "Tipos de Categoria de Conta a Pagar inseridos com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $estado = new ContaPagarCategoria();
        $estado->setId($id);
        $estado->setDescricao($descricao);

        $manager->persist($estado);
    }
}

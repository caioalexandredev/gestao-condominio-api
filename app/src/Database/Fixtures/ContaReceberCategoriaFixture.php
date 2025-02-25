<?php

namespace App\Database\Fixtures;

use App\Entity\ContaReceberCategoria;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class ContaReceberCategoriaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->cadastrar($manager, 1, "Mensalidade");
        $this->cadastrar($manager, 2, "Serviço");
        $this->cadastrar($manager, 3, "Produto");

        $manager->flush();

        echo "Tipos de Categoria de Conta a Receber inseridos com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $estado = new ContaReceberCategoria();
        $estado->setId($id);
        $estado->setDescricao($descricao);

        $manager->persist($estado);
    }
}

<?php

namespace App\Database\Fixtures;

use App\Entity\InformativoVisibilidade;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class InformativoVisibilidadeFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->cadastrar($manager, 1, "Público");
        $this->cadastrar($manager, 2, "Interno");
        $this->cadastrar($manager, 3, "Privado");

        $manager->flush();

        echo "Visibilidade de Informativos inseridos com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $estado = new InformativoVisibilidade();
        $estado->setId($id);
        $estado->setDescricao($descricao);

        $manager->persist($estado);
    }
}

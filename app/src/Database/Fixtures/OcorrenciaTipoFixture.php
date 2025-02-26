<?php

namespace App\Database\Fixtures;

use App\Entity\OcorrenciaTipo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class OcorrenciaTipoFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->cadastrar($manager, 1, "Ruído");
        $this->cadastrar($manager, 2, "Segurança");
        $this->cadastrar($manager, 3, "Outro");

        $manager->flush();

        echo "Ocorrência inseridas com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $estado = new OcorrenciaTipo();
        $estado->setId($id);
        $estado->setDescricao($descricao);

        $manager->persist($estado);
    }
}

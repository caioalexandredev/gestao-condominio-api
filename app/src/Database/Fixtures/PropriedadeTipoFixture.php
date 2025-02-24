<?php

namespace App\Database\Fixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Estado;
use App\Entity\Cidade;
use App\Entity\PropriedadeTipo;
use Doctrine\Common\DataFixtures\AbstractFixture;

class PropriedadeTipoFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->cadastrar($manager, 1, "Lote");
        $this->cadastrar($manager, 2, "Sala Comercial");
        $this->cadastrar($manager, 3, "Casa");
        $this->cadastrar($manager, 4, "Apartamento");
        $this->cadastrar($manager, 5, "Chácara");

        $manager->flush();

        echo "Tipos de Propriedade inseridos com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $estado = new PropriedadeTipo();
        $estado->setId($id);
        $estado->setIdDescricao($descricao);

        $manager->persist($estado);
    }
}

<?php

namespace App\Database\Fixtures;

use App\Entity\Informativo;
use App\Entity\InformativoVisibilidade;
use App\Entity\Usuario;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker\Factory as Faker;

class InformativoFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('pt_BR');

        $usuario = $manager->getRepository(Usuario::class)->findOneBy([]);
        if (!$usuario) {
            throw new \Exception("Nenhum usuário encontrado.");
        }

        $visibilidades = $manager->getRepository(InformativoVisibilidade::class)->findAll();
        if (empty($visibilidades)) {
            throw new \Exception("Nenhuma visibilidade encontrada.");
        }

        for ($i = 0; $i < 20; $i++) {
            $informativo = new Informativo();

            $informativo->setAssunto($faker->sentence(6));
            $informativo->setInformacao($faker->paragraph(3));
            $informativo->setVisibilidade($faker->randomElement($visibilidades));
            $informativo->setUsuario($usuario);
            $informativo->setDtInclusao($faker->dateTimeBetween('-1 year', 'now'));
            $informativo->setAtivo($faker->boolean(90));

            $manager->persist($informativo);
        }

        $manager->flush();
        echo "20 informativos aleatórios foram cadastrados com sucesso!\n";
    }
}

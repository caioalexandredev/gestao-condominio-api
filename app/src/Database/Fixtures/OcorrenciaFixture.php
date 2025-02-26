<?php

namespace App\Database\Fixtures;

use App\Entity\Ocorrencia;
use App\Entity\OcorrenciaTipo;
use App\Entity\Usuario;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker\Factory as Faker;

class OcorrenciaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('pt_BR');

        $usuario = $manager->getRepository(Usuario::class)->findOneBy([]);
        if (!$usuario) {
            throw new \Exception("Nenhum usuário encontrado.");
        }

        $tipos = $manager->getRepository(OcorrenciaTipo::class)->findAll();
        if (empty($tipos)) {
            throw new \Exception("Nenhum tipo de ocorrência encontrado.");
        }

        for ($i = 0; $i < 20; $i++) {
            $ocorrencia = new Ocorrencia();

            $ocorrencia->setAssunto($faker->sentence(6));
            $ocorrencia->setDescricao($faker->paragraph(3));
            $ocorrencia->setDtOcorrencia($faker->dateTimeBetween('-1 year', 'now'));
            $ocorrencia->setTipo($faker->randomElement($tipos));
            $ocorrencia->setUsuario($usuario);
            $ocorrencia->setAtivo($faker->boolean(90));

            $manager->persist($ocorrencia);
        }

        $manager->flush();
        echo "20 ocorrências aleatórias foram cadastradas com sucesso!\n";
    }
}

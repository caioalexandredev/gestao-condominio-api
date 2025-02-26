<?php

namespace App\Database\Fixtures;

use App\Entity\ContaPagar;
use App\Entity\ContaTipo;
use App\Entity\ContaStatus;
use App\Entity\ContaPagarCategoria;
use App\Entity\Usuario;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker\Factory as Faker;

class ContaPagarFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('pt_BR');

        $usuario = $manager->getRepository(Usuario::class)->findOneBy([]);
        if (!$usuario) {
            throw new \Exception("Nenhum usuário encontrado.");
        }

        $tipos = $manager->getRepository(ContaTipo::class)->findAll();
        if (empty($tipos)) {
            throw new \Exception("Nenhum tipo de conta encontrado.");
        }

        $statusList = $manager->getRepository(ContaStatus::class)->findAll();
        if (empty($statusList)) {
            throw new \Exception("Nenhum status de conta encontrado.");
        }

        $categorias = $manager->getRepository(ContaPagarCategoria::class)->findAll();
        if (empty($categorias)) {
            throw new \Exception("Nenhuma categoria de conta encontrada.");
        }

        for ($i = 0; $i < 20; $i++) {
            $contaPagar = new ContaPagar();

            $contaPagar->setDescricao($faker->sentence(6));
            $contaPagar->setValor($faker->randomFloat(2, 100, 5000));
            $contaPagar->setVencimento($faker->dateTimeBetween('now', '+1 year'));
            $contaPagar->setObservacao($faker->sentence(10));
            $contaPagar->setTipo($faker->randomElement($tipos));
            $contaPagar->setStatus($faker->randomElement($statusList));
            $contaPagar->setCategoria($faker->randomElement($categorias));
            $contaPagar->setUsuario($usuario);
            $contaPagar->setAtivo($faker->boolean(90));

            $manager->persist($contaPagar);
        }

        $manager->flush();
        echo "20 contas a pagar aleatórias foram cadastradas com sucesso!\n";
    }
}

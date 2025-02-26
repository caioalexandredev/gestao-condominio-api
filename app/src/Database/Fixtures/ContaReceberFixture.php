<?php

namespace App\Database\Fixtures;

use App\Entity\ContaReceber;
use App\Entity\ContaTipo;
use App\Entity\ContaStatus;
use App\Entity\ContaReceberCategoria;
use App\Entity\Usuario;
use App\Entity\Propriedade;
use App\Entity\PessoaDados;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker\Factory as Faker;

class ContaReceberFixture extends AbstractFixture
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

        $categorias = $manager->getRepository(ContaReceberCategoria::class)->findAll();
        if (empty($categorias)) {
            throw new \Exception("Nenhuma categoria de conta encontrada.");
        }

        $propriedades = $manager->getRepository(Propriedade::class)->findAll();
        if (empty($propriedades)) {
            throw new \Exception("Nenhuma propriedade encontrada.");
        }

        $proprietarios = $manager->getRepository(PessoaDados::class)->findAll();
        if (empty($proprietarios)) {
            throw new \Exception("Nenhum proprietário encontrado.");
        }

        for ($i = 0; $i < 20; $i++) {
            $contaReceber = new ContaReceber();

            $contaReceber->setDescricao($faker->sentence(6));
            $contaReceber->setValor($faker->randomFloat(2, 100, 5000));
            $contaReceber->setVencimento($faker->dateTimeBetween('now', '+1 year'));
            $contaReceber->setObservacao($faker->sentence(10));
            $contaReceber->setTipo($faker->randomElement($tipos));
            $contaReceber->setStatus($faker->randomElement($statusList));
            $contaReceber->setCategoria($faker->randomElement($categorias));
            $contaReceber->setPropriedade($faker->randomElement($propriedades));
            $contaReceber->setProprietario($faker->randomElement($proprietarios));
            $contaReceber->setUsuario($usuario);
            $contaReceber->setAtivo($faker->boolean(90));

            $manager->persist($contaReceber);
        }

        $manager->flush();
        echo "20 contas a receber aleatórias foram cadastradas com sucesso!\n";
    }
}

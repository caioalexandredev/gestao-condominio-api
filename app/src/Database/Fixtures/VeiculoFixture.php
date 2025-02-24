<?php

namespace App\Database\Fixtures;

use App\Entity\Veiculo;
use App\Entity\VeiculoMarca;
use App\Entity\VeiculoCor;
use App\Entity\VeiculoCategoria;
use App\Entity\Propriedade;
use App\Entity\PessoaDados;
use App\Entity\Usuario;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker\Factory as Faker;

class VeiculoFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('pt_BR');

        $usuario = $manager->getRepository(Usuario::class)->findOneBy([]);
        if (!$usuario) {
            throw new \Exception("Nenhum usuário encontrado.");
        }

        $marcas = $manager->getRepository(VeiculoMarca::class)->findAll();
        if (empty($marcas)) {
            throw new \Exception("Nenhuma marca de veículo encontrada.");
        }

        $cores = $manager->getRepository(VeiculoCor::class)->findAll();
        if (empty($cores)) {
            throw new \Exception("Nenhuma cor de veículo encontrada.");
        }

        $categorias = $manager->getRepository(VeiculoCategoria::class)->findAll();
        if (empty($categorias)) {
            throw new \Exception("Nenhuma categoria de veículo encontrada.");
        }

        $propriedades = $manager->getRepository(Propriedade::class)->findAll();
        if (empty($propriedades)) {
            throw new \Exception("Nenhuma propriedade encontrada.");
        }

        $proprietarios = $manager->getRepository(PessoaDados::class)->findAll();
        if (empty($proprietarios)) {
            throw new \Exception("Nenhuma pessoa (proprietário) encontrada.");
        }

        $modelos = ['Civic', 'Corolla', 'Gol', 'Fiesta', 'Onix', 'Uno', 'Focus', 'Clio'];

        for ($i = 0; $i < 20; $i++) {
            $veiculo = new Veiculo();

            $veiculo->setPlaca($faker->bothify('???####'));
            $veiculo->setModelo($faker->randomElement($modelos));
            $veiculo->setAno($faker->year);

            $veiculo->setMarca($faker->randomElement($marcas));
            $veiculo->setCor($faker->randomElement($cores));
            $veiculo->setCategoria($faker->randomElement($categorias));
            $veiculo->setPropriedade($faker->randomElement($propriedades));
            $veiculo->setProprietario($faker->randomElement($proprietarios));
            $veiculo->setUsuario($usuario);

            $manager->persist($veiculo);
        }

        $manager->flush();
        echo "20 veículos aleatórios foram cadastrados com sucesso!\n";
    }
}

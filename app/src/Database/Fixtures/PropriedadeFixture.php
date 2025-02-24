<?php

namespace App\Database\Fixtures;

use App\Entity\PessoaDados;
use App\Entity\Endereco;
use App\Entity\Cidade;
use App\Entity\Propriedade;
use App\Entity\PropriedadeTipo;
use App\Entity\Usuario;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker\Factory as Faker;

class PropriedadeFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('pt_BR');

        $usuario = $manager->getRepository(Usuario::class)->findOneBy([]);

        $cidades = $manager->getRepository(Cidade::class)->findAll();
        if (empty($cidades)) {
            throw new \Exception("Nenhuma cidade encontrada. Execute a fixture de cidades primeiro.");
        }

        $propretarios = $manager->getRepository(PessoaDados::class)->findAll();
        $tipos = $manager->getRepository(PropriedadeTipo::class)->findAll();

        for ($i = 0; $i < 20; $i++) {
            $endereco = new Endereco();
            $cidade = $cidades[array_rand($cidades)];
            $endereco->setCidade($cidade);
            $endereco->setNumero($faker->buildingNumber);
            $endereco->setComplemento($faker->secondaryAddress);
            $endereco->setBairro($faker->streetName);
            $endereco->setLogradouro($faker->streetAddress);
            $endereco->setCep($faker->postcode);
            $endereco->setDtInclusao(new \DateTime());
            $endereco->setUsuario($usuario);
            $manager->persist($endereco);

            $propretario = $propretarios[array_rand($propretarios)];
            $tipo = $tipos[array_rand($tipos)];

            $pessoa = new Propriedade();
            $pessoa->setEndereco($endereco);
            $pessoa->setProprietario($propretario);
            $pessoa->setTipo($tipo);
            $pessoa->setObservacao("Observação da propriedade");
            $pessoa->setDtInclusao(new \DateTime());
            $pessoa->setUsuario($usuario);
            $manager->persist($pessoa);
        }

        $manager->flush();
        echo "20 propriedades aleatórias foram cadastradas com sucesso!\n";
    }
}

<?php

namespace App\Database\Fixtures;

use App\Entity\PessoaDados;
use App\Entity\Endereco;
use App\Entity\Cidade;
use App\Entity\Contato;
use App\Entity\Usuario;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker\Factory as Faker;

class PessoaDadosFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('pt_BR');

        $usuario = $manager->getRepository(Usuario::class)->findOneBy([]);

        $cidades = $manager->getRepository(Cidade::class)->findAll();
        if (empty($cidades)) {
            throw new \Exception("Nenhuma cidade encontrada. Execute a fixture de cidades primeiro.");
        }

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

            $pessoa = new PessoaDados();
            $pessoa->setTelefone($this->cadastrarContato(1, $faker->phoneNumber, $usuario, $manager));
            $pessoa->setCelular($this->cadastrarContato(2, $faker->cellphoneNumber, $usuario, $manager));
            $pessoa->setEmail($this->cadastrarContato(3, $faker->email, $usuario, $manager));
            $pessoa->setNome($faker->firstName);
            $pessoa->setSobrenome($faker->lastName);
            $pessoa->setDataNascimento($faker->dateTimeBetween('-80 years', '-18 years'));
            $pessoa->setSexo($faker->randomElement(['M', 'F']));
            $pessoa->setCpf($faker->numerify('###########'));
            $pessoa->setNaturalidade($faker->city);
            $pessoa->setRg($faker->numerify('#########'));
            $pessoa->setOrgaoEmissorRg($faker->randomElement(['SSP', 'Detran', 'Polícia Militar']));
            $pessoa->setDataEmissaoRg($faker->dateTimeBetween('-30 years', 'now'));
            $pessoa->setEndereco($endereco);
            $pessoa->setDtInclusao(new \DateTime());
            $pessoa->setUsuario($usuario);
            $manager->persist($pessoa);
        }

        $manager->flush();
        echo "20 pessoas aleatórias foram cadastradas com sucesso!\n";
    }

    public function cadastrarContato(
        int $tipo,
        string $informacao,
        Usuario $usuario,
        ObjectManager $manager
    ): Contato
    {
        $contato = new Contato();
        $contato->setContato($informacao);
        $contato->setTipo($tipo);
        $contato->setUsuario($usuario);

        $manager->persist($contato);

        return $contato;
    }
}

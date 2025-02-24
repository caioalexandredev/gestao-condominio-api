<?php

namespace App\Database\Fixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\VeiculoMarca;
use Doctrine\Common\DataFixtures\AbstractFixture;

class VeiculoMarcaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        echo "Iniciando inserção de marcas de veiculos.\n";

        $url = 'https://parallelum.com.br/fipe/api/v1/carros/marcas';

        $json = file_get_contents($url);
        if ($json === false) {
            echo "Erro ao acessar a API.\n";
            return;
        }

        $marcas = json_decode($json, true);
        if ($marcas === null) {
            echo "Erro ao decodificar os dados da API.\n";
            return;
        }

        foreach ($marcas as $marca) {
            $id = (int)$marca['codigo'];
            $descricao = $marca['nome'];

            $this->cadastrar($manager, $id, $descricao);
        }

        $manager->flush();

        echo "Marcas de veículos inseridas com sucesso!\n";
    }

    private function cadastrar(ObjectManager $manager, int $id, string $descricao): void
    {
        $veiculoMarca = new VeiculoMarca();
        $veiculoMarca->setId($id);
        $veiculoMarca->setDescricao($descricao);

        $manager->persist($veiculoMarca);
    }
}

<?php

namespace App\Database\Fixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Estado;
use App\Entity\Cidade;
use Doctrine\Common\DataFixtures\AbstractFixture;

class EstadoCidadeApiFixture extends AbstractFixture
{
    private string $urlEstados = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';
    private string $urlCidades = 'https://servicodados.ibge.gov.br/api/v1/localidades/municipios';

    public function load(ObjectManager $manager): void
    {
        echo "Buscando estados...\n";
        $estados = $this->fetchData($this->urlEstados);
        $estadosMap = [];

        foreach ($estados as $estadoData) {
            $estado = new Estado();
            $estado->setNome($estadoData['nome']);
            $estado->setUf($estadoData['sigla']);

            $manager->persist($estado);
            $estadosMap[$estadoData['id']] = $estado;
        }
        $manager->flush();

        echo "Buscando cidades...\n";
        $cidades = $this->fetchData($this->urlCidades);

        foreach ($cidades as $cidadeData) {
            $idEstadoIbge = $cidadeData['microrregiao']['mesorregiao']['UF']['id'];

            if (isset($estadosMap[$idEstadoIbge])) {
                $cidade = new Cidade();
                $cidade->setNome($cidadeData['nome']);
                $cidade->setEstado($estadosMap[$idEstadoIbge]);

                $manager->persist($cidade);
            }
        }
        $manager->flush();

        echo "Estados e cidades inseridos com sucesso!\n";
    }

    private function fetchData(string $url): array
    {
        $response = file_get_contents($url);
        return json_decode($response, true) ?? [];
    }
}

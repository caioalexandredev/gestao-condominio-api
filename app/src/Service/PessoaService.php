<?php

namespace App\Service;

use App\Entity\PessoaDados;
use Doctrine\ORM\EntityManager;

class PessoaService
{
    public function __construct(
        private EntityManager $em,
        private EnderecoService $enderecoService,
        private PessoaDadosService $pessoaDadosService
    )
    {
        
    }

    public function cadastrar(
        array $data
    ): PessoaDados
    {
        try {
            $this->em->getConnection()->beginTransaction();

            $endereco = $this->enderecoService->cadastrar($data);
            $pessoaDados = $this->pessoaDadosService->cadastrar($data, $endereco);

            $this->em->getConnection()->commit();

            return $pessoaDados;

        } catch (\Throwable $th) {
            $this->em->getConnection()->rollBack();
            throw $th;
        }
    }
}
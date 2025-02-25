<?php

namespace App\Service;

use App\Entity\ContaPagarCategoria;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class ContaPagarCategoriaService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?ContaPagarCategoria
    {
        return $this->em->find(ContaPagarCategoria::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('cpc.id AS key', 'cpc.descricao AS value')
            ->from(ContaPagarCategoria::class, 'cpc');

        return $qb->orderBy('cpc.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
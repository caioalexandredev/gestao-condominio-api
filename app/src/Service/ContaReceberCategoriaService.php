<?php

namespace App\Service;

use App\Entity\ContaReceberCategoria;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class ContaReceberCategoriaService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?ContaReceberCategoria
    {
        return $this->em->find(ContaReceberCategoria::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('crc.id AS key', 'crc.descricao AS value')
            ->from(ContaReceberCategoria::class, 'crc');

        return $qb->orderBy('crc.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
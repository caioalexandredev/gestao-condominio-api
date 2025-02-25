<?php

namespace App\Service;

use App\Entity\ContaStatus;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class ContaStatusService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?ContaStatus
    {
        return $this->em->find(ContaStatus::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('cs.id AS key', 'cs.descricao AS value')
            ->from(ContaStatus::class, 'cs');

        return $qb->orderBy('cs.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
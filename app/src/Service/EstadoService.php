<?php

namespace App\Service;

use App\Entity\Estado;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class EstadoService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('e.id', 'e.uf')
            ->from(Estado::class, 'e');

        return $qb->orderBy('e.uf', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
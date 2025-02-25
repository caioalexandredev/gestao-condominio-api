<?php

namespace App\Service;

use App\Entity\ContaTipo;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class ContaTipoService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?ContaTipo
    {
        return $this->em->find(ContaTipo::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('ct.id AS key', 'ct.descricao AS value')
            ->from(ContaTipo::class, 'ct');

        return $qb->orderBy('ct.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
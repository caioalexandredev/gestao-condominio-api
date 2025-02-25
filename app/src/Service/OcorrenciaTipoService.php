<?php

namespace App\Service;

use App\Entity\OcorrenciaTipo;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class OcorrenciaTipoService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?OcorrenciaTipo
    {
        return $this->em->find(OcorrenciaTipo::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('ot.id AS key', 'ot.descricao AS value')
            ->from(OcorrenciaTipo::class, 'ot');

        return $qb->orderBy('ot.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
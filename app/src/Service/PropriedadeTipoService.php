<?php

namespace App\Service;

use App\Entity\PropriedadeTipo;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class PropriedadeTipoService
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

        $qb->select('pt.id AS key', 'pt.descricao AS value')
            ->from(PropriedadeTipo::class, 'pt');

        return $qb->orderBy('pt.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
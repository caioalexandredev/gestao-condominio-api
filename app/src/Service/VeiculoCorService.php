<?php

namespace App\Service;

use App\Entity\VeiculoCor;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class VeiculoCorService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?VeiculoCor
    {
        return $this->em->find(VeiculoCor::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('vc.id AS key', 'vc.descricao AS value')
            ->from(VeiculoCor::class, 'vc');

        return $qb->orderBy('vc.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
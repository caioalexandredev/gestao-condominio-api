<?php

namespace App\Service;

use App\Entity\Cidade;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class CidadeService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function select(?string $estado = null): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('c.id', 'c.nome')
            ->from(Cidade::class, 'c')
            ->join('c.estado', 'e');

        if($estado){
            $qb->andWhere($qb->expr()->in('e.uf', ':uf'))
                ->setParameter('uf', $estado);
        }else{
            $qb->andWhere($qb->expr()->in('e.uf', ':uf'))
                ->setParameter('uf', "TO");
        }

        return $qb->orderBy('c.nome', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function selectOptionKey(?string $estado = null): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('c.id AS key', 'c.nome AS value')
            ->from(Cidade::class, 'c')
            ->join('c.estado', 'e');

        if($estado){
            $qb->andWhere($qb->expr()->in('e.uf', ':uf'))
                ->setParameter('uf', $estado);
        }else{
            $qb->andWhere($qb->expr()->in('e.uf', ':uf'))
                ->setParameter('uf', "TO");
        }

        return $qb->orderBy('c.nome', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
<?php

namespace App\Service;

use App\Entity\InformativoVisibilidade;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class InformativoVisibilidadeService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?InformativoVisibilidade
    {
        return $this->em->find(InformativoVisibilidade::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('iv.id AS key', 'iv.descricao AS value')
            ->from(InformativoVisibilidade::class, 'iv');

        return $qb->orderBy('iv.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
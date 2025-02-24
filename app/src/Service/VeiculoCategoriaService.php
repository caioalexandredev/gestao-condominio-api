<?php

namespace App\Service;

use App\Entity\VeiculoCategoria;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class VeiculoCategoriaService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?VeiculoCategoria
    {
        return $this->em->find(VeiculoCategoria::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('vc.id AS key', 'vc.descricao AS value')
            ->from(VeiculoCategoria::class, 'vc');

        return $qb->orderBy('vc.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
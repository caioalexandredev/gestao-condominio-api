<?php

namespace App\Service;

use App\Entity\VeiculoMarca;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class VeiculoMarcaService
{
    public function __construct(
        private EntityManager $em,
        private SessionInterface $session
    )
    {
    }

    public function consultar(int $id): ?VeiculoMarca
    {
        return $this->em->find(VeiculoMarca::class, $id);
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('vm.id AS key', 'vm.descricao AS value')
            ->from(VeiculoMarca::class, 'vm');

        return $qb->orderBy('vm.descricao', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
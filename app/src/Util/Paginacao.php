<?php
namespace App\Util;

use Doctrine\ORM\Query;

class Paginacao{
    public static function prepararListagem(Query $query, int $pageSize, int $page, $extra = []): array
    {
        $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $paginator->setUseOutputWalkers(false);

        $total = count($paginator);

        $pagesCount = ceil($total / $pageSize);

        $paginator->getQuery()
                    ->setFirstResult($pageSize * ($page-1))
                    ->setMaxResults($pageSize)->getSQL();

        $result = array();

        foreach ($paginator as $pageItem) {
            $result[] = $pageItem;
        }
        
        return ['resultado' => $result, 'paginas' => $pagesCount, 'total' => $total, 'extra' => $extra];
    }
}
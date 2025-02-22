<?php
namespace App\Util;

class Paginacao{

    public static function paginar($qb, $page, $pageSize, $isArray = true, $extra = []){
        $page = $page >= 1 ? $page : 1;
        $pageSize = $pageSize >= 1 && $pageSize <= 100 ? $pageSize : 10;

        $firstResult = ($page - 1) * $pageSize;

        $qb->setFirstResult($firstResult)
            ->setMaxResults($pageSize);

        $query = $qb->getQuery();

        if($isArray){
            $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }

        $result = $query->getResult();

        $countQuery = clone $query;
        $countQuery->setFirstResult(null)->setMaxResults(null);

        $parameters = $query->getParameters();

        foreach ($parameters as $parameter) {
            $countQuery->setParameter($parameter->getName(), $parameter->getValue());
        }

        $totalItems = count($countQuery->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));

        $pagesCount = ceil($totalItems / $pageSize);

        return ['result' => $result, 'paginas' => $pagesCount, 'total' => $totalItems, 'extra' => $extra];
    }
}
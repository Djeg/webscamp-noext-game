<?php

namespace App\Symfony\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use App\Cookery\Recipe\Collection;

class RecipesList implements Collection
{
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return \App\Symfony\Entity\Recipe[]
     */
    public function all()
    {
        return $this
            ->queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }
}

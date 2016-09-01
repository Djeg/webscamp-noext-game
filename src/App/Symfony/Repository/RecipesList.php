<?php

namespace App\Symfony\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;

class RecipesList
{
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return \App\Symfony\Entity\Recipe[]
     */
    public function findAll()
    {
        return $this
            ->queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }
}

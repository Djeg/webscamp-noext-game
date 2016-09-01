<?php

namespace App\Symfony\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use App\Cookery\Recipe\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RecipesList extends EntityRepository implements Collection
{
    /**
     * @return \App\Symfony\Entity\Recipe[]
     */
    public function all()
    {
        return $this
            ->createQueryBuilder('recipe')
            ->getQuery()
            ->getResult()
        ;
    }

    public function one($identifier)
    {
        $recipe = $this->find($identifier);

        if (null == $recipe) {
            throw new NotFoundHttpException();
        }

        return $recipe;
    }
}

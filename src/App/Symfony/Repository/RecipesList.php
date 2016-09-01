<?php

namespace App\Symfony\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class RecipesList extends EntityRepository
{
    /**
     * @param Request $request
     *
     * @return \App\Symfony\Entity\Recipe[]
     */
    public function findAllAndPaginate(Request $request)
    {
        $offset = (int)$request->query->get('offset', 0);
        $limit = (int)$request->query->get('limit', 25);
        $orderBy = $request->query->get('orderBy', 'r.id');

        return $this
            ->createQueryBuilder('r')
            ->orderBy($orderBy)
            ->getQuery()
            ->getResult()
        ;
    }
}

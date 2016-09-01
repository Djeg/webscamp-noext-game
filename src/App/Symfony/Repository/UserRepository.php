<?php

namespace App\Symfony\Repository;

use Doctrine\ORM\EntityRepository;
use App\Symfony\Entity\User;

class UserRepository extends EntityRepository
{
    public function findUserLike(User $user)
    {
        return $this->findOneBy([
            'username' => $user->getUsername(),
        ]);
    }
}

<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserLibrary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserLibrary|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLibrary|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLibrary[]    findAll()
 * @method UserLibrary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLibraryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserLibrary::class);
    }

}

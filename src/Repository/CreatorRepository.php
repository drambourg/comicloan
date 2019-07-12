<?php

namespace App\Repository;

use App\Entity\Creator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Creator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Creator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Creator[]    findAll()
 * @method Creator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreatorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Creator::class);
    }


}

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

    public function findUserLibraryByCountLoanable($limit=5, $order='ASC')
    {
        return $this->createQueryBuilder('ul')
            ->select('COUNT(ul.id) as count')
            ->addSelect('ul.comicId')
            ->groupBy('ul.comicId')
            ->Where('ul.isLoanable = :isLoanable')
            ->setParameter('isLoanable', true)
            ->setMaxResults($limit)
            ->addOrderBy('count',$order)
            ->getQuery()
            ->getResult();
    }

    public function findUserLibraryByCount($limit=5, $order='ASC')
    {
        return $this->createQueryBuilder('ul')
            ->select('COUNT(ul.id) as count')
            ->join('ul.user', 'u')
            ->addSelect('u.id')
            ->groupBy('ul.user')
            ->setMaxResults($limit)
            ->addOrderBy('count',$order)
            ->getQuery()
            ->getResult();
    }

    public function findUserLibraryAvailable($limit=5, $order='ASC')
    {
        return $this->createQueryBuilder('ul')
            ->select('COUNT(ul.id) as count')
            ->addSelect('ul.comicId')
            ->groupBy('ul.comicId')
            ->Where('ul.isLoanable = :isLoanable')
            ->setParameter('isLoanable', true)
            ->anndWhere('ul.isLoanable = :isLoanable')
            ->setParameter('isLoanable', true)
            ->setMaxResults($limit)
            ->addOrderBy('count',$order)
            ->getQuery()
            ->getResult();
    }
}

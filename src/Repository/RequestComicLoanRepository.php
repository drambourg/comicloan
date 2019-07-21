<?php

namespace App\Repository;

use App\Entity\RequestComicLoan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RequestComicLoan|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestComicLoan|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestComicLoan[]    findAll()
 * @method RequestComicLoan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestComicLoanRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RequestComicLoan::class);
    }

    // /**
    //  * @return RequestComicLoan[] Returns an array of RequestComicLoan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequestComicLoan
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

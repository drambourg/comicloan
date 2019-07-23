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

    public function findComicByCountRequested($limit=5, $order='ASC')
    {
        return $this->createQueryBuilder('rq')
            ->select('COUNT(rq.id) as count')
            ->addSelect('rq.comicId')

            ->groupBy('rq.comicId')
            ->setMaxResults($limit)
            ->addOrderBy('count',$order)
            ->getQuery()
            ->getResult();
    }
}

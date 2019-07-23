<?php

namespace App\Repository;

use App\Entity\ComicLoan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComicLoan|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComicLoan|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComicLoan[]    findAll()
 * @method ComicLoan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicLoanRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComicLoan::class);
    }

    public function findUserLoanerByCountLoan($limit = 5, $order = 'DESC')
    {
        return $this->createQueryBuilder('cl')
            ->select('COUNT(cl.id) as count')
            ->join('cl.UserLoaner', 'u')
            ->addSelect('u.id')
            ->groupBy('cl.UserLoaner')
            ->setMaxResults($limit)
            ->addOrderBy('count', $order)
            ->getQuery()
            ->getResult();

    }

    public function findLastLoanerFromComicId(int $idComic, int $limitResult = 3)
    {
        return $this->createQueryBuilder('cl')
            ->join('cl.userLibrary', 'ul')
            ->andWhere('ul.comicId = :comicId')
            ->setParameter('comicId', $idComic)
            ->orderBy('cl.dateOut', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function findUserLibraryAvailable($limit = 0, $order = 'ASC')
    {
        $qb = $this->createQueryBuilder('cl')
            ->join('cl.userLibrary', "ul")
            ->select('ul.comicId')
            ->groupBy('cl.userLibrary')
            ->addGroupBy('ul.comicId')
            ->andHaving('count(cl.id) = SUM(cl.status)')
            ->addOrderBy('ul.comicId', $order)
            ->getQuery();

        return array_map('current', $qb->getScalarResult());

    }

    public function AllLoansFromComicId(int $idComic)
    {
        return $this->createQueryBuilder('cl')
            ->join('cl.userLibrary', 'ul')
            ->andWhere('ul.comicId = :comicId')
            ->setParameter('comicId', $idComic)
            ->orderBy('cl.dateOut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function AllLoansSinceDateFromComicId(int $idComic, \DateTime $limitResultDate)
    {
        return $this->createQueryBuilder('cl')
            ->join('cl.userLibrary', 'ul')
            ->andWhere('ul.comicId = :comicId')
            ->setParameter('comicId', $idComic)
            ->andWhere('cl.dateOut >= :datelimit')
            ->setParameter('datelimit', $limitResultDate)
            ->orderBy('cl.dateOut', 'DESC')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return ComicLoan[] Returns an array of ComicLoan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComicLoan
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

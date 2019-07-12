<?php

namespace App\Repository;

use App\Entity\Comic;
use App\Service\APIConnect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comic[]    findAll()
 * @method Comic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicRepository extends ServiceEntityRepository
{
    const BASE_URI ='/v1/public/comics';

    private $apiComicConnect;
    private $apiConnect;
    private $comicConverter;


    public function __construct(RegistryInterface $registry, APIConnect $apiConnect,ComicConverter $comicConverter)
    {
        parent::__construct($registry, Comic::class);
        $this->comicConverter = $comicConverter;
        $this->apiConnect = $apiConnect;
        $this->apiComicConnect = $apiConnect->getApiurl() . self::BASE_URI;
    }

    /**
     * @return string
     */
    public function getApiComicConnect(): string
    {
        return $this->apiComicConnect;
    }

    /**
     * @param string $apiCharacterConnect
     */
    public function setApiComicConnect(string $apiComicConnect): void
    {
        $this->apiComicConnect = $apiComicConnect;
    }

    /**
     * @return mixed
     */
    public function getApiConnect()
    {
        return $this->apiConnect;
    }

    /**
     * @param mixed $apiConnect
     */
    public function setApiConnect($apiConnect): void
    {
        $this->apiConnect = $apiConnect;
    }
}

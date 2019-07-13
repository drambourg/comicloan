<?php

namespace App\Repository;

use App\Entity\Character;
use App\Service\APIConnect;
use App\Service\CharacterConverter;
use App\Service\ComicConverter;
use App\Service\CreatorConverter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    private $apiConnect;
    private $characterConverter;
    private $comicConverter;
    private $creatorConverter;


    public function __construct(
        RegistryInterface $registry,
        APIConnect $apiConnect,
        CharacterConverter $characterConverter,
        ComicConverter $comicConverter,
        CreatorConverter $creatorConverter

    )
    {
        parent::__construct($registry, Character::class);
        $this->characterConverter = $characterConverter;
        $this->comicConverter = $comicConverter;
        $this->creatorConverter = $creatorConverter;
        $this->apiConnect = $apiConnect;
    }

    /**
     * @param array $criteria
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findAllCharacters(array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CHARACTER, [
            'query' => $query
        ]);

        return $this->characterConverter->ConvertResponseToCharacterEntities($response->toArray());
    }

    /**
     * @param int $characterId
     * @param array $criteria
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findCharacterById(int $characterId, array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CHARACTER . '/' . $characterId,
            [
                'query' => $query
            ]);

        return $this->characterConverter->ConvertResponseToCharacterEntities($response->toArray());
    }

    /**
     * @param int $characterId
     * @param array $criteria
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findComicsByCharacterId(int $characterId, array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CHARACTER . '/' . $characterId . '/comics',
            [
                'query' => $query
            ]);

        return $this->comicConverter->ConvertResponseToComicEntities($response->toArray());
    }

        /**
     * @return string
     */
    public function getApiCharacterConnect(): string
    {
        return $this->apiCharacterConnect;
    }

    /**
     * @param string $apiCharacterConnect
     */
    public function setApiCharacterConnect(string $apiCharacterConnect): void
    {
        $this->apiCharacterConnect = $apiCharacterConnect;
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


    // /**
    //  * @return Character[] Returns an array of Character objects
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
    public function findOneBySomeField($value): ?Character
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

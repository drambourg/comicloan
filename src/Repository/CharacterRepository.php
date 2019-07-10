<?php

namespace App\Repository;

use App\Entity\Character;
use App\Service\APIConnect;
use App\Service\CharacterConverter;
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
    const BASE_URI ='/v1/public/characters';

    private $apiCharacterConnect;
    private $apiConnect;
    private $characterConverter;


    public function __construct(RegistryInterface $registry, APIConnect $apiConnect,CharacterConverter $characterConverter)
    {
        parent::__construct($registry, Character::class);
        $this->characterConverter = $characterConverter;
        $this->apiConnect = $apiConnect;
        $this->apiCharacterConnect = $apiConnect->getApiurl() . self::BASE_URI;
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

    public function findallCharactersByLimit(): array
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiCharacterConnect,[
            'query' => [
                'ts' => $this->apiConnect->getApiTS(),
                'apikey' => $this->apiConnect->getApiPublicKey(),
                'hash' => $this->apiConnect->getApihash(),
            ]
            ]);

        return $this->characterConverter->ConvertResponseToCharacterEntities($response->toArray());
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

<?php

namespace App\Repository;

use App\Entity\Creator;
use App\Service\APIConnect;
use App\Service\CreatorConverter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @method Creator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Creator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Creator[]    findAll()
 * @method Creator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreatorRepository extends ServiceEntityRepository
{
    private $apiCreatorConnect;
    private $apiConnect;
    private $creatorConverter;


    public function __construct(
        RegistryInterface $registry,
        APIConnect $apiConnect,
        CreatorConverter $creatorConverter)
    {
        parent::__construct($registry, Creator::class);
        $this->creatorConverter = $creatorConverter;
        $this->apiConnect = $apiConnect;
        $this->apiCreatorConnect = $apiConnect->getApiurl() . $apiConnect::BASE_URI_CREATOR;
    }

    public function findAllComics(array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiCreatorConnect,[
            'query' => $query
        ]);

        return $this->creatorConverter->ConvertResponseToComicEntities($response->toArray());
    }


}

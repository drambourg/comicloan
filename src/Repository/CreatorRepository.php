<?php

namespace App\Repository;

use App\Entity\Creator;
use App\Service\APIConnect;
use App\Service\ComicConverter;
use App\Service\CreatorConverter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpClient\HttpClient;

class CreatorRepository
{
    private $apiConnect;
    private $creatorConverter;

    public function __construct(
        RegistryInterface $registry,
        APIConnect $apiConnect,
        CreatorConverter $creatorConverter
    ) {
        $this->apiConnect = $apiConnect;
        $this->creatorConverter = $creatorConverter;

    }


    public function findAllCreators(array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CREATOR,[
            'query' => $query
        ]);

        return $this->creatorConverter->ConvertResponseToCreatorEntities($response->toArray());
    }

    public function findCreatorById(int $creatorId, array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
                $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CREATOR. '/' . $creatorId,
            [
                'query' => $query
            ]);

        return $this->creatorConverter->ConvertResponseToCreatorEntities($response->toArray());
    }

    public function findAllCreatorsFromComicId(int $comicId, array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_COMIC. '/' . $comicId . '/creators',
            [
                'query' => $query
            ]);

        return $this->creatorConverter->ConvertResponseToCreatorEntities($response->toArray());
    }

}

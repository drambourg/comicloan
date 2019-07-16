<?php

namespace App\Repository;

use App\Entity\Character;
use App\Entity\Comic;
use App\Service\APIConnect;
use App\Service\ComicConverter;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpClient\HttpClient;


class ComicRepository
{
    private $apiConnect;
    private $comicConverter;

    public function __construct(
        RegistryInterface $registry,
        APIConnect $apiConnect,
        ComicConverter $comicConverter)
    {
        $this->comicConverter = $comicConverter;
        $this->apiConnect = $apiConnect;
    }

    public function findAllComics(array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_COMIC,[
            'query' => $query
        ]);

        return $this->comicConverter->ConvertResponseToComicEntities($response->toArray());
    }

    public function findComicById(int $comicId, array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_COMIC . '/' . $comicId,[
            'query' => $query
        ]);

        return $this->comicConverter->ConvertResponseToComicEntities($response->toArray());
    }

    public function findAllComicsFromCharacterId(int $characterId, array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CHARACTER . '/' . $characterId . '/comics',
            [
            'query' => $query,
        ]);

        return $this->comicConverter->ConvertResponseToComicEntities($response->toArray());
    }


    public function findAllComicsFromCreatorId(int $creatorId, array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CREATOR. '/' . $creatorId . '/comics',
            [
                'query' => $query
            ]);

        return $this->comicConverter->ConvertResponseToComicEntities($response->toArray());
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

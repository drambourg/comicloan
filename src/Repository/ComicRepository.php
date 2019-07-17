<?php

namespace App\Repository;

use App\Service\APIConnect;
use App\Service\ComicConverter;
use Symfony\Component\HttpClient\HttpClient;


class ComicRepository
{
    private $apiConnect;
    private $comicConverter;

    public function __construct(
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

        if (200 !== $response->getStatusCode()) {
            return [];
        } else {
            $results=$response->toArray();
            return [
                'count' => $results['data']['total'],
                'comics' => $this->comicConverter->ConvertResponseToComicEntities($results),
            ];
        }
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

        if (200 !== $response->getStatusCode()) {
            return [];
        } else {
            $results=$response->toArray();
            return [
                'count' => $results['data']['total'],
                'comics' => $this->comicConverter->ConvertResponseToComicEntities($results),
            ];
        }
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
        if (200 !== $response->getStatusCode()) {
            return [];
        } else {
            $results=$response->toArray();
            return [
                'count' => $results['data']['total'],
                'comics' => $this->comicConverter->ConvertResponseToComicEntities($results),
            ];
        }
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

        if (200 !== $response->getStatusCode()) {
            return [];
        } else {
            $results=$response->toArray();
            return [
                'count' => $results['data']['total'],
                'comics' => $this->comicConverter->ConvertResponseToComicEntities($results),
            ];
        }
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

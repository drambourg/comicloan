<?php

namespace App\Repository;

use App\Entity\Character;
use App\Service\APIConnect;
use App\Service\CharacterConverter;

use Symfony\Component\HttpClient\HttpClient;


class CharacterRepository
{
    private $apiConnect;
    private $characterConverter;


    public function __construct(
        APIConnect $apiConnect,
        CharacterConverter $characterConverter

    )
    {
        $this->characterConverter = $characterConverter;
        $this->apiConnect = $apiConnect;
    }


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

        if (200 !== $response->getStatusCode()) {
            return [];
        } else {
            $results=$response->toArray();
            return [
                'count' => $results['data']['total'],
                'characters' =>$this->characterConverter->ConvertResponseToCharacterEntities($results),
            ];
        }
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
    public function findCharacterById(int $characterId, array $criteria = []): Character
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

        if (200 !== $response->getStatusCode()) {
            return [];
        } else {
            $results=$response->toArray();
            return [
                'count' => $results['data']['total'],
                'characters' =>$this->characterConverter->ConvertResponseToCharacterEntities($results),
            ];
        }
    }

    /**
     * @param int $comicId
     * @param array $criteria
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findCharactersByComicId(int $comicId, array $criteria = []): array
    {
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_COMIC . '/' . $comicId . '/characters',
            [
                'query' => $query
            ]);

        if (200 !== $response->getStatusCode()) {
            return [];
        } else {
            $results=$response->toArray();
            return [
                'count' => $results['data']['total'],
                'characters' =>$this->characterConverter->ConvertResponseToCharacterEntities($results),
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

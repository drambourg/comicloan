<?php

namespace App\Repository;

use App\Entity\Character;
use App\Entity\Comic;
use App\Service\APIConnect;
use App\Service\ComicConverter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @method Comic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comic[]    findAll()
 * @method Comic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicRepository extends ServiceEntityRepository
{
    private $apiComicConnect;
    private $apiConnect;
    private $comicConverter;
    private $characterRepository;


    public function __construct(
        RegistryInterface $registry,
        APIConnect $apiConnect,
        ComicConverter $comicConverter,
        CharacterRepository $characterRepository)
    {
        parent::__construct($registry, Comic::class);
        $this->comicConverter = $comicConverter;
        $this->apiConnect = $apiConnect;
        $this->characterRepository = $characterRepository;
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

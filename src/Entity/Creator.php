<?php

namespace App\Entity;

use App\Service\APIConnect;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpClient\HttpClient;

class Creator
{
    private $id;

    private $firstName;

    private $middleName;

    private $lastName;

    private $suffix;

    private $fullName;

    private $thumbnailPath;

    private $thumbnailExtension;

    private $comics;

    private $apiConnect;

    public function __construct(APIConnect $apiConnect)
    {
        $this->apiConnect = $apiConnect;
        $this->comics = new ArrayCollection();
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstname(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function setSuffix(?string $suffix): self
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getThumbnailPath(): ?string
    {
        return $this->thumbnailPath;
    }

    public function setThumbnailPath(?string $thumbnailPath): self
    {
        $this->thumbnailPath = $thumbnailPath;

        return $this;
    }

    public function getThumbnailExtension(): ?string
    {
        return $this->thumbnailExtension;
    }

    public function setThumbnailExtension(?string $thumbnailExtension): self
    {
        $this->thumbnailExtension = $thumbnailExtension;

        return $this;
    }

    public function getComics(array $criteria = []): array
    {
        $comics =[];

        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CREATOR. '/' . $this->id . '/comics',
            [
                'query' => $query
            ]);

        $comicValues = $response->toArray();
        $comicData = $comicValues['data']['results'];
        foreach ($comicData as $comicDatum) {
            $comic = new Comic( $this->apiConnect);
            $comic->setId($comicDatum['id']);
            $comic->setDigitalId($comicDatum['digitalId']);
            $comic->setTitle($comicDatum['title']);
            $comic->setIssueNumber($comicDatum['issueNumber']);
            $comic->setVariantDescription($comicDatum['variantDescription']);
            $comic->setDescription($comicDatum['description']);
            $comic->setModified(new \DateTime($comicDatum['modified']));
            $comic->setIsbn($comicDatum['isbn']);
            $comic->setFormat($comicDatum['format']);
            $comic->setPageCount($comicDatum['pageCount']);
            $comic->setDetailUrl($comicDatum['urls'][0]['url']??null);
            $comic->setPurchaseURL($comicDatum['urls'][1]['url']??null);
            $comic->setOnsaleDate(new \DateTime($comicDatum['dates'][0]['date'])??null);
            $comic->setPrintPrice($comicDatum['prices'][0]['price']??null);
            $comic->setDigitalPurchasePrice($comicDatum['prices'][1]['price']??null);
            $comic->setThumbnailPath($comicDatum['thumbnail']['path']??null);
            $comic->setThumbnailExtension($comicDatum['thumbnail']['extension']??null);
            $comics[] = $comic;
        }

        return $comics;
    }

    public function addComic(Comic $comic): self
    {
        if (!$this->comics->contains($comic)) {
            $this->comics[] = $comic;
        }

        return $this;
    }

    public function removeComic(Comic $comic): self
    {
        if ($this->comics->contains($comic)) {
            $this->comics->removeElement($comic);
        }

        return $this;
    }


}

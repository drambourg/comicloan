<?php

namespace App\Entity;

use App\Service\APIConnect;
use App\Service\ComicConverter;
use App\Service\Picture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CharacterRepository")
 */
class Character
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $modified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resourceURI;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnailPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnailExtension;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picturePath;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Comic", inversedBy="characters")
     */
    private $comics;

    private $apiConnect;

    public function __construct(
        APIConnect $apiConnect
     ) {
        $this->apiConnect =  $apiConnect;
        $this->comics = new ArrayCollection();
    }


    /**
     * @param integer $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified->format("Y") != '-0001'? $this->modified:null;
    }

    public function setModified(?\DateTimeInterface $modified): self
    {
        $this->modified = $modified;

        return $this;
    }

    public function getResourceURI(): ?string
    {
        return $this->resourceURI;
    }

    public function setResourceURI(?string $resourceURI): self
    {
        $this->resourceURI = $resourceURI;

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

    public function getPicture(): ?Picture
    {
        $picture = new Picture($this->thumbnailPath,$this->thumbnailExtension );
        return $picture;
    }


    public function getComics(array $criteria = []): array
    {
        $comics=[];
        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_CHARACTER . '/' . $this->id . '/comics',
            [
                'query' => $query,
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

    /**
     * @return mixed
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * @param mixed $picturePath
     */
    public function setPicturePath($picturePath): void
    {
        $this->picturePath = $picturePath;
    }

}

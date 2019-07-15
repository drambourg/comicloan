<?php

namespace App\Entity;

use App\Service\APIConnect;
use App\Service\Picture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpClient\HttpClient;


class Comic
{

    private $id;

    private $digitalId;

    private $title;

    private $issueNumber;

    private $variantDescription;

    private $description;

    private $modified;

    private $isbn;

    private $format;

    private $pageCount;

    private $detailUrl;

    private $purchaseURL;

    private $onsaleDate;

    private $digitalPurchaseDate;

    private $printPrice;

    private $digitalPurchasePrice;

    private $characters;

    private $images;

    private $thumbnailPath;

    private $thumbnailExtension;

    private $creators;

    private $apiConnect;

    public function __construct(
        APIConnect $apiConnect)
    {
        $this->apiConnect =  $apiConnect;
        $this->characters = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->creators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getDigitalId(): ?int
    {
        return $this->digitalId;
    }

    public function setDigitalId(?int $digitalId): self
    {
        $this->digitalId = $digitalId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIssueNumber(): ?int
    {
        return $this->issueNumber;
    }

    public function setIssueNumber(?int $issueNumber): self
    {
        $this->issueNumber = $issueNumber;

        return $this;
    }

    public function getVariantDescription(): ?string
    {
        return $this->variantDescription;
    }

    public function setVariantDescription(?string $variantDescription): self
    {
        $this->variantDescription = $variantDescription;

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

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function setPageCount(?int $pageCount): self
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    public function getDetailUrl(): ?string
    {
        return $this->detailUrl;
    }

    public function setDetailUrl(?string $detailUrl): self
    {
        $this->detailUrl = $detailUrl;

        return $this;
    }

    public function getPurchaseUrl(): ?string
    {
        return $this->purchaseURL;
    }

    public function setPurchaseUrl(?string $purchaseURL): self
    {
        $this->purchaseURL = $purchaseURL;

        return $this;
    }

    public function getOnsaleDate(): ?\DateTimeInterface
    {
        return $this->onsaleDate->format("Y") != '-0001'? $this->onsaleDate:null;
    }

    public function setOnsaleDate(?\DateTimeInterface $onsaleDate): self
    {
        $this->onsaleDate = $onsaleDate;

        return $this;
    }

    public function getDigitalPurchaseDate(): ?\DateTimeInterface
    {
        return $this->digitalPurchaseDate->format("Y") != '-0001'? $this->digitalPurchaseDate:null;
    }

    public function setDigitalPurchaseDate(?\DateTimeInterface $digitalPurchaseDate): self
    {
        $this->digitalPurchaseDate = $digitalPurchaseDate;

        return $this;
    }

    public function getPrintPrice(): ?float
    {
        return $this->printPrice;
    }

    public function setPrintPrice(?float $printPrice): self
    {
        $this->printPrice = $printPrice;

        return $this;
    }

    public function getDigitalPurchasePrice(): ?float
    {
        return $this->digitalPurchasePrice;
    }

    public function setDigitalPurchasePrice(?float $digitalPurchasePrice): self
    {
        $this->digitalPurchasePrice = $digitalPurchasePrice;

        return $this;
    }

    public function getCharacters(array $criteria = []): array
    {
        $characters =[];

        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_COMIC . '/' . $this->id . '/characters',
            [
                'query' => $query
            ]);
        $characterValues = $response->toArray();
        $charactersData = $characterValues['data']['results'];
        foreach ($charactersData as $characterDatum) {
            $character = new Character($this->apiConnect);
            $character->setId($characterDatum['id']);
            $character->setName($characterDatum['name']);
            $character->setDescription($characterDatum['description']);
            $character->setModified(new \DateTimeImmutable($characterDatum['modified']));
            $character->setThumbnailPath($characterDatum['thumbnail']['path']);
            $character->setThumbnailExtension($characterDatum['thumbnail']['extension']);
            $character->setResourceURI($characterDatum['resourceURI']);
            /*            $comics = $this->comicRepository->findAllComicsFromCharacterId($characterDatum['id']);
                        foreach ($comics as $comic) {
                            $character->addComic($comic);
                        }*/
            $characters[] = $character;
        }
        return $characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->addComic($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->contains($character)) {
            $this->characters->removeElement($character);
            $character->removeComic($this);
        }

        return $this;
    }

    /**
     * @return Collection|ComicPicture[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ComicPicture $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setComic($this);
        }

        return $this;
    }

    public function removeImage(ComicPicture $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getComic() === $this) {
                $image->setComic(null);
            }
        }

        return $this;
    }

    public function getThumbnailPicture(): ?Picture
    {
        $picture = new Picture($this->thumbnailPath??'',$this->thumbnailExtension??'');
        return $picture;
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

    public function getCreators(array $criteria = []): array
    {
        $creators =[];


        $query = $this->apiConnect->baseParamsConnect();
        $query = array_merge($query, $criteria);

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            $this->apiConnect->getApiurl() . $this->apiConnect::BASE_URI_COMIC. '/' . $this->id . '/creators',
            [
                'query' => $query
            ]);

        $creatorValues = $response->toArray();
        $creatorData = $creatorValues['data']['results'];
        foreach ($creatorData as $creatorDatum) {
            $creator = new Creator($this->apiConnect);
            $creator->setId($creatorDatum['id']);
            $creator->setFirstName($creatorDatum['firstName']);
            $creator->setMiddleName($creatorDatum['middleName']);
            $creator->setLastName($creatorDatum['lastName']);
            $creator->setSuffix($creatorDatum['suffix']);
            $creator->setFullName($creatorDatum['fullName']);
            $creator->setThumbnailPath($creatorDatum['thumbnail']['path']??null);
            $creator->setThumbnailExtension($creatorDatum['thumbnail']['extension']??null);
            $creators[] = $creator;
        }
        return $creators;
    }

    public function addCreator(Creator $creator): self
    {
        if (!$this->creators->contains($creator)) {
            $this->creators[] = $creator;
            $creator->addComic($this);
        }

        return $this;
    }

    public function removeCreator(Creator $creator): self
    {
        if ($this->creators->contains($creator)) {
            $this->creators->removeElement($creator);
            $creator->removeComic($this);
        }

        return $this;
    }


}

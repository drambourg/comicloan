<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComicRepository")
 */
class Comic
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $digitalId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $issueNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $variantDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $format;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pageCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $detailUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $detailPurchase;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $onsaleDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $digitalPurchaseDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $printPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $digitalPurchasePrice;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Character", mappedBy="comics")
     */
    private $characters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ComicPicture", mappedBy="comic")
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnailPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnailExtension;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->modified;
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

    public function getDetailPurchase(): ?string
    {
        return $this->detailPurchase;
    }

    public function setDetailPurchase(?string $detailPurchase): self
    {
        $this->detailPurchase = $detailPurchase;

        return $this;
    }

    public function getOnsaleDate(): ?\DateTimeInterface
    {
        return $this->onsaleDate;
    }

    public function setOnsaleDate(?\DateTimeInterface $onsaleDate): self
    {
        $this->onsaleDate = $onsaleDate;

        return $this;
    }

    public function getDigitalPurchaseDate(): ?\DateTimeInterface
    {
        return $this->digitalPurchaseDate;
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

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
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
}

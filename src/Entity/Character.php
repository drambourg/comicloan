<?php

namespace App\Entity;

use App\Service\Picture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    public function __construct()
    {
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

    /**
     * @return Collection|Comic[]
     */
    public function getComics(): Collection
    {
        return $this->comics;
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

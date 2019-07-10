<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CharacterRepository")
 */
class Character
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
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
        return $this->modified;
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
}

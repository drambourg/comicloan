<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


class ComicPicture
{

    private $id;

    private $path;

    private $extension;

    private $comic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getComic(): ?Comic
    {
        return $this->comic;
    }

    public function setComic(?Comic $comic): self
    {
        $this->comic = $comic;

        return $this;
    }
}

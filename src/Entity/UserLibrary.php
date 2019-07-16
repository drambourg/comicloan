<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserLibraryRepository")
 */
class UserLibrary
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $comicId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $loanable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userLibraries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComicId(): ?int
    {
        return $this->comicId;
    }

    public function setComicId(int $comicId): self
    {
        $this->comicId = $comicId;

        return $this;
    }

    public function getLoanable(): ?bool
    {
        return $this->loanable;
    }

    public function setLoanable(bool $loanable): self
    {
        $this->loanable = $loanable;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $isLoanable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userLibraries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ComicLoan", mappedBy="userLibrary")
     * @ORM\OrderBy({"dateOut" = "DESC"})
     */
    private $comicLoans;


    public function __construct()
    {
        $this->comicLoans = new ArrayCollection();
    }

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

    public function getIsLoanable(): ?bool
    {
        return $this->isLoanable;
    }

    public function setIsLoanable(bool $IsLoanable): self
    {
        $this->isLoanable = $IsLoanable;

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

    /**
     * @return Collection|ComicLoan[]
     */
    public function getComicLoans(): Collection
    {
        return $this->comicLoans;
    }

    public function addComicLoan(ComicLoan $comicLoan): self
    {
        if (!$this->comicLoans->contains($comicLoan)) {
            $this->comicLoans[] = $comicLoan;
            $comicLoan->setUserLibrary($this);
        }

        return $this;
    }

    public function removeComicLoan(ComicLoan $comicLoan): self
    {
        if ($this->comicLoans->contains($comicLoan)) {
            $this->comicLoans->removeElement($comicLoan);
            // set the owning side to null (unless already changed)
            if ($comicLoan->getUserLibrary() === $this) {
                $comicLoan->setUserLibrary(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComicLoanRepository")
 */
class ComicLoan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateOut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateIn;

    /**
     * @ORM\Column(type="boolean")
     */
    private $view;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comicLoans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserLoaner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserLibrary", inversedBy="comicLoans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userLibrary;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateOut(): ?\DateTimeInterface
    {
        return $this->dateOut;
    }

    public function setDateOut(?\DateTimeInterface $dateOut): self
    {
        $this->dateOut = $dateOut;

        return $this;
    }

    public function getDateIn(): ?\DateTimeInterface
    {
        return $this->dateIn;
    }

    public function setDateIn(?\DateTimeInterface $dateIn): self
    {
        $this->dateIn = $dateIn;

        return $this;
    }

    public function getView(): ?bool
    {
        return $this->view;
    }

    public function setView(bool $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getUserLoaner(): ?User
    {
        return $this->UserLoaner;
    }

    public function setUserLoaner(?User $UserLoaner): self
    {
        $this->UserLoaner = $UserLoaner;

        return $this;
    }

    public function getUserLibrary(): ?UserLibrary
    {
        return $this->userLibrary;
    }

    public function setUserLibrary(?UserLibrary $userLibrary): self
    {
        $this->userLibrary = $userLibrary;

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRateRepository")
 */
class UserRate
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
    private $rate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userRates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCommment(): ?string
    {
        return $this->commment;
    }

    public function setCommment(?string $commment): self
    {
        $this->commment = $commment;

        return $this;
    }

    public function getDateAt(): ?\DateTimeInterface
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeInterface $dateAt): self
    {
        $this->dateAt = $dateAt;

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

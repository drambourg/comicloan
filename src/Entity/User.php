<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"pseudoname"}, message="There is already an account with this pseudoname")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Fill a pseudo")
     * @Assert\Length(max="255",maxMessage="Le pseudo {{ value }} have too much characters, {{ limit }} characters maximum")
     */
    private $pseudoname;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Fill a city")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatarPicture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Select a country")
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Fill an email")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserLibrary", mappedBy="user", orphanRemoval=true)
     */
    private $userLibraries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ComicLoan", mappedBy="UserLoaner")
     */
    private $comicLoans;

    public function __construct()
    {
        $this->setRoles(['ROLE_AUTHOR']);
        $this->userLibraries = new ArrayCollection();
        $this->comicLoans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudoname(): ?string
    {
        return $this->pseudoname;
    }

    public function setPseudoname(string $pseudoname): self
    {
        $this->pseudoname = $pseudoname;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->pseudoname;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAvatarPicture(): ?string
    {
        return $this->avatarPicture;
    }

    public function setAvatarPicture(?string $avatarPicture): self
    {
        $this->avatarPicture = $avatarPicture;

        return $this;
    }

    public function getDateCreated(): ?\DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTime $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|UserLibrary[]
     */
    public function getUserLibraries(): Collection
    {
        return $this->userLibraries;
    }

    public function addUserLibrary(UserLibrary $userLibrary): self
    {
        if (!$this->userLibraries->contains($userLibrary)) {
            $this->userLibraries[] = $userLibrary;
            $userLibrary->setUser($this);
        }

        return $this;
    }

    public function removeUserLibrary(UserLibrary $userLibrary): self
    {
        if ($this->userLibraries->contains($userLibrary)) {
            $this->userLibraries->removeElement($userLibrary);
            // set the owning side to null (unless already changed)
            if ($userLibrary->getUser() === $this) {
                $userLibrary->setUser(null);
            }
        }

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
            $comicLoan->setUserLoaner($this);
        }

        return $this;
    }

    public function removeComicLoan(ComicLoan $comicLoan): self
    {
        if ($this->comicLoans->contains($comicLoan)) {
            $this->comicLoans->removeElement($comicLoan);
            // set the owning side to null (unless already changed)
            if ($comicLoan->getUserLoaner() === $this) {
                $comicLoan->setUserLoaner(null);
            }
        }

        return $this;
    }
}

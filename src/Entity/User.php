<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Dream::class)]
    private $dreams;

    #[ORM\ManyToMany(targetEntity: Dream::class, mappedBy: 'likedBy')]
    private $likedDreams;

    #[ORM\Column(type: 'bigint', nullable: true)]
    private $facebookId;

    public function __construct()
    {
        $this->dreams = new ArrayCollection();
        $this->likedDreams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return strtolower($this->email);
    }

    public function setEmail(string $email): self
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Dream>
     */
    public function getDreams(): Collection
    {
        return $this->dreams;
    }

    public function addDream(Dream $dream): self
    {
        if (!$this->dreams->contains($dream)) {
            $this->dreams[] = $dream;
            $dream->setAuthor($this);
        }

        return $this;
    }

    public function removeDream(Dream $dream): self
    {
        if ($this->dreams->removeElement($dream)) {
            // set the owning side to null (unless already changed)
            if ($dream->getAuthor() === $this) {
                $dream->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dream>
     */
    public function getLikedDreams(): Collection
    {
        return $this->likedDreams;
    }

    public function addLikedDream(Dream $likedDream): self
    {
        if (!$this->likedDreams->contains($likedDream)) {
            $this->likedDreams[] = $likedDream;
            $likedDream->addLikedBy($this);
        }

        return $this;
    }

    public function removeLikedDream(Dream $likedDream): self
    {
        if ($this->likedDreams->removeElement($likedDream)) {
            $likedDream->removeLikedBy($this);
        }

        return $this;
    }

    public function getFacebookId(): ?int
    {
        return $this->facebookId;
    }

    public function setFacebookId(?int $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }
}

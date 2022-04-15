<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: Dream::class)]
    private $dreams;

    public function __construct()
    {
        $this->dreams = new ArrayCollection();
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
            $dream->setTheme($this);
        }

        return $this;
    }

    public function removeDream(Dream $dream): self
    {
        if ($this->dreams->removeElement($dream)) {
            // set the owning side to null (unless already changed)
            if ($dream->getTheme() === $this) {
                $dream->setTheme(null);
            }
        }

        return $this;
    }
}

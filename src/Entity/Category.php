<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Dream::class)]
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
            $dream->setCategory($this);
        }

        return $this;
    }

    public function removeDream(Dream $dream): self
    {
        if ($this->dreams->removeElement($dream)) {
            // set the owning side to null (unless already changed)
            if ($dream->getCategory() === $this) {
                $dream->setCategory(null);
            }
        }

        return $this;
    }
}

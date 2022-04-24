<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 2)]
    private $code;

    #[ORM\Column(type: 'string', length: 10)]
    private $hemisphere;

    #[ORM\Column(type: 'string', length: 10)]
    private $coverage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getHemisphere(): ?string
    {
        return $this->hemisphere;
    }

    public function setHemisphere(string $hemisphere): self
    {
        $this->hemisphere = $hemisphere;

        return $this;
    }

    public function getCoverage(): ?string
    {
        return $this->coverage;
    }

    public function setCoverage(string $coverage): self
    {
        $this->coverage = $coverage;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ChangeLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChangeLogRepository::class)]
class ChangeLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Time = null;

    #[ORM\Column(length: 40)]
    private ?string $ItemCode = null;

    #[ORM\Column(length: 255)]
    private ?string $Action = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Details = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->Time;
    }

    public function setTime(\DateTimeInterface $Time): static
    {
        $this->Time = $Time;

        return $this;
    }

    public function getItemCode(): ?string
    {
        return $this->ItemCode;
    }

    public function setItemCode(string $ItemCode): static
    {
        $this->ItemCode = $ItemCode;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->Action;
    }

    public function setAction(string $Action): static
    {
        $this->Action = $Action;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->Details;
    }

    public function setDetails(string $Details): static
    {
        $this->Details = $Details;

        return $this;
    }
}

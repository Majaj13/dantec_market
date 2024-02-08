<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavorisRepository::class)]
class Favoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lesFavoris')]
    private ?User $leUser = null;

    #[ORM\ManyToOne(inversedBy: 'LesFavoris')]
    private ?Produits $leProduit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeUser(): ?User
    {
        return $this->leUser;
    }

    public function setLeUser(?User $leUser): static
    {
        $this->leUser = $leUser;

        return $this;
    }

    public function getLeProduit(): ?Produits
    {
        return $this->leProduit;
    }

    public function setLeProduit(?Produits $leProduit): static
    {
        $this->leProduit = $leProduit;

        return $this;
    }
}

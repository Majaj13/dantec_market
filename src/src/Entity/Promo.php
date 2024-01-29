<?php

namespace App\Entity;

use App\Repository\PromoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromoRepository::class)]
class Promo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'lesPromos')]
    private ?Produits $leProduit = null;

    #[ORM\ManyToOne(inversedBy: 'lesPromos')]
    private ?CategoriePromo $laCategoriePromo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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

    public function getLaCategoriePromo(): ?CategoriePromo
    {
        return $this->laCategoriePromo;
    }

    public function setLaCategoriePromo(?CategoriePromo $laCategoriePromo): static
    {
        $this->laCategoriePromo = $laCategoriePromo;

        return $this;
    }
}

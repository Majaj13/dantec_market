<?php

namespace App\Entity;

use App\Repository\RéserverRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RéserverRepository::class)]
class Réserver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure = null;

    #[ORM\ManyToOne(inversedBy: 'lesReservations')]
    private ?User $leUser = null;

    #[ORM\ManyToOne(inversedBy: 'lesReservations')]
    private ?Planning $lePlanning = null;

    #[ORM\ManyToOne(inversedBy: 'lesReservations')]
    private ?Commandes $laCommande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
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

    public function getLePlanning(): ?Planning
    {
        return $this->lePlanning;
    }

    public function setLePlanning(?Planning $lePlanning): static
    {
        $this->lePlanning = $lePlanning;

        return $this;
    }

    public function getLaCommande(): ?Commandes
    {
        return $this->laCommande;
    }

    public function setLaCommande(?Commandes $laCommande): static
    {
        $this->laCommande = $laCommande;

        return $this;
    }
}

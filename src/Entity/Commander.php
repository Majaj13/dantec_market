<?php

namespace App\Entity;

use App\Repository\CommanderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommanderRepository::class)]
class Commander
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'lesCommandes')]
    private ?Commandes $laCommande = null;

    #[ORM\ManyToOne(inversedBy: 'lesCommandes')]
    private ?Produits $leProduit = null;

    #[ORM\Column]
    private ?float $prixretenu = null;

    #[ORM\Column]
    private ?bool $noteDonnee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

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

    public function getLeProduit(): ?Produits
    {
        return $this->leProduit;
    }

    public function setLeProduit(?Produits $leProduit): static
    {
        $this->leProduit = $leProduit;

        return $this;
    }

    public function getPrixretenu(): ?float
    {
        return $this->prixretenu;
    }

    public function setPrixretenu(float $prixretenu): static
    {
        $this->prixretenu = $prixretenu;

        return $this;
    }

    public function isNoteDonnee(): ?bool
    {
        return $this->noteDonnee;
    }

    public function setNoteDonnee(bool $noteDonnee): static
    {
        $this->noteDonnee = $noteDonnee;

        return $this;
    }
}

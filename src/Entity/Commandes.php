<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column]
    private ?float $montantTotal = null;

    #[ORM\ManyToOne(inversedBy: 'lesCommandes')]
    private ?User $leUser = null;

    #[ORM\OneToMany(mappedBy: 'laCommande', targetEntity: Réserver::class)]
    private Collection $lesReservations;

    #[ORM\OneToMany(mappedBy: 'laCommande', targetEntity: Commander::class)]
    private Collection $lesCommandes;

    public function __construct()
    {
        $this->lesReservations = new ArrayCollection();
        $this->lesCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

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

    /**
     * @return Collection<int, Réserver>
     */
    public function getLesReservations(): Collection
    {
        return $this->lesReservations;
    }

    public function addLesReservation(Réserver $lesReservation): static
    {
        if (!$this->lesReservations->contains($lesReservation)) {
            $this->lesReservations->add($lesReservation);
            $lesReservation->setLaCommande($this);
        }

        return $this;
    }

    public function removeLesReservation(Réserver $lesReservation): static
    {
        if ($this->lesReservations->removeElement($lesReservation)) {
            // set the owning side to null (unless already changed)
            if ($lesReservation->getLaCommande() === $this) {
                $lesReservation->setLaCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commander>
     */
    public function getLesCommandes(): Collection
    {
        return $this->lesCommandes;
    }

    public function addLesCommande(Commander $lesCommande): static
    {
        if (!$this->lesCommandes->contains($lesCommande)) {
            $this->lesCommandes->add($lesCommande);
            $lesCommande->setLaCommande($this);
        }

        return $this;
    }

    public function removeLesCommande(Commander $lesCommande): static
    {
        if ($this->lesCommandes->removeElement($lesCommande)) {
            // set the owning side to null (unless already changed)
            if ($lesCommande->getLaCommande() === $this) {
                $lesCommande->setLaCommande(null);
            }
        }

        return $this;
    }
}
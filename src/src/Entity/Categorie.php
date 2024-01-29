<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'laCategorie', targetEntity: Produits::class)]
    private Collection $lesProduits;

    #[ORM\ManyToOne(inversedBy: 'lescategories')]
    private ?CategorieParent $lacategorieParent = null;

    public function __construct()
    {
        $this->lesProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Produits>
     */
    public function getLesProduits(): Collection
    {
        return $this->lesProduits;
    }

    public function addLesProduit(Produits $lesProduit): static
    {
        if (!$this->lesProduits->contains($lesProduit)) {
            $this->lesProduits->add($lesProduit);
            $lesProduit->setLaCategorie($this);
        }

        return $this;
    }

    public function removeLesProduit(Produits $lesProduit): static
    {
        if ($this->lesProduits->removeElement($lesProduit)) {
            // set the owning side to null (unless already changed)
            if ($lesProduit->getLaCategorie() === $this) {
                $lesProduit->setLaCategorie(null);
            }
        }

        return $this;
    }

    public function getLacategorieParent(): ?CategorieParent
    {
        return $this->lacategorieParent;
    }

    public function setLacategorieParent(?CategorieParent $lacategorieParent): static
    {
        $this->lacategorieParent = $lacategorieParent;

        return $this;
    }
}

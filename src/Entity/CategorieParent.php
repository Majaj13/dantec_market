<?php

namespace App\Entity;

use App\Repository\CategorieParentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieParentRepository::class)]
class CategorieParent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'lacategorieParent', targetEntity: Categorie::class)]
    private Collection $lescategories;

    public function __construct()
    {
        $this->lescategories = new ArrayCollection();
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
     * @return Collection<int, Categorie>
     */
    public function getLescategories(): Collection
    {
        return $this->lescategories;
    }

    public function addLescategory(Categorie $lescategory): static
    {
        if (!$this->lescategories->contains($lescategory)) {
            $this->lescategories->add($lescategory);
            $lescategory->setLacategorieParent($this);
        }

        return $this;
    }

    public function removeLescategory(Categorie $lescategory): static
    {
        if ($this->lescategories->removeElement($lescategory)) {
            // set the owning side to null (unless already changed)
            if ($lescategory->getLacategorieParent() === $this) {
                $lescategory->setLacategorieParent(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->nom;
    }
}

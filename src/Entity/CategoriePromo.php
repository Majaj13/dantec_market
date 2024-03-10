<?php

namespace App\Entity;

use App\Repository\CategoriePromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriePromoRepository::class)]
class CategoriePromo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'laCategoriePromo', targetEntity: Promo::class)]
    private Collection $lesPromos;

    public function __construct()
    {
        $this->lesPromos = new ArrayCollection();
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
     * @return Collection<int, Promo>
     */
    public function getLesPromos(): Collection
    {
        return $this->lesPromos;
    }

    public function addLesPromo(Promo $lesPromo): static
    {
        if (!$this->lesPromos->contains($lesPromo)) {
            $this->lesPromos->add($lesPromo);
            $lesPromo->setLaCategoriePromo($this);
        }

        return $this;
    }

    public function removeLesPromo(Promo $lesPromo): static
    {
        if ($this->lesPromos->removeElement($lesPromo)) {
            // set the owning side to null (unless already changed)
            if ($lesPromo->getLaCategoriePromo() === $this) {
                $lesPromo->setLaCategoriePromo(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        // Return the property that best represents this entity, e.g., name
        return $this->nom;
    }
}

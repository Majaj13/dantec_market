<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\ManyToMany(targetEntity: Produits::class, mappedBy: 'lesImages')]
    private Collection $lesProduits;

    #[ORM\ManyToOne(inversedBy: 'lesImages')]
    private ?Actualite $laActualite = null;

    public function __construct()
    {
        $this->lesProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

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
            $lesProduit->addLesImage($this);
        }

        return $this;
    }

    public function removeLesProduit(Produits $lesProduit): static
    {
        if ($this->lesProduits->removeElement($lesProduit)) {
            $lesProduit->removeLesImage($this);
        }

        return $this;
    }

    public function getLaActualite(): ?Actualite
    {
        return $this->laActualite;
    }

    public function setLaActualite(?Actualite $laActualite): static
    {
        $this->laActualite = $laActualite;

        return $this;
    }
}

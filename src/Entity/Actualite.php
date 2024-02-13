<?php

namespace App\Entity;

use App\Repository\ActualiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActualiteRepository::class)]
class Actualite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'laActualite', targetEntity: Images::class)]
    private Collection $lesImages;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    public function __construct()
    {
        $this->lesImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getLesImages(): Collection
    {
        return $this->lesImages;
    }

    public function addLesImage(Images $lesImage): static
    {
        if (!$this->lesImages->contains($lesImage)) {
            $this->lesImages->add($lesImage);
            $lesImage->setLaActualite($this);
        }

        return $this;
    }

    public function removeLesImage(Images $lesImage): static
    {
        if ($this->lesImages->removeElement($lesImage)) {
            // set the owning side to null (unless already changed)
            if ($lesImage->getLaActualite() === $this) {
                $lesImage->setLaActualite(null);
            }
        }

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }
}

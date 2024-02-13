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

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    #[ORM\OneToMany(mappedBy: 'laActualite', targetEntity: Images::class)]
    private Collection $lesimages;

    public function __construct()
    {
        $this->lesimages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Images>
     */
    public function getLesimages(): Collection
    {
        return $this->lesimages;
    }

    public function addLesimage(Images $lesimage): static
    {
        if (!$this->lesimages->contains($lesimage)) {
            $this->lesimages->add($lesimage);
            $lesimage->setLaActualite($this);
        }

        return $this;
    }

    public function removeLesimage(Images $lesimage): static
    {
        if ($this->lesimages->removeElement($lesimage)) {
            // set the owning side to null (unless already changed)
            if ($lesimage->getLaActualite() === $this) {
                $lesimage->setLaActualite(null);
            }
        }

        return $this;
    }
}

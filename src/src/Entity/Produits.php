<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomProduit = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $quantiteDisponible = null;

    #[ORM\OneToMany(mappedBy: 'leProduit', targetEntity: Commander::class)]
    private Collection $lesCommandes;

    #[ORM\OneToMany(mappedBy: 'leProduit', targetEntity: Commentaires::class)]
    private Collection $lesCommentaires;

    #[ORM\OneToMany(mappedBy: 'leProduit', targetEntity: Promo::class)]
    private Collection $lesPromos;

    #[ORM\ManyToMany(targetEntity: Images::class, inversedBy: 'lesProduits')]
    private Collection $lesImages;

    #[ORM\ManyToOne(inversedBy: 'lesProduits')]
    private ?Categorie $laCategorie = null;

    public function __construct()
    {
        $this->lesCommandes = new ArrayCollection();
        $this->lesCommentaires = new ArrayCollection();
        $this->lesPromos = new ArrayCollection();
        $this->lesImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): static
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getQuantiteDisponible(): ?int
    {
        return $this->quantiteDisponible;
    }

    public function setQuantiteDisponible(int $quantiteDisponible): static
    {
        $this->quantiteDisponible = $quantiteDisponible;

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
            $lesCommande->setLeProduit($this);
        }

        return $this;
    }

    public function removeLesCommande(Commander $lesCommande): static
    {
        if ($this->lesCommandes->removeElement($lesCommande)) {
            // set the owning side to null (unless already changed)
            if ($lesCommande->getLeProduit() === $this) {
                $lesCommande->setLeProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getLesCommentaires(): Collection
    {
        return $this->lesCommentaires;
    }

    public function addLesCommentaire(Commentaires $lesCommentaire): static
    {
        if (!$this->lesCommentaires->contains($lesCommentaire)) {
            $this->lesCommentaires->add($lesCommentaire);
            $lesCommentaire->setLeProduit($this);
        }

        return $this;
    }

    public function removeLesCommentaire(Commentaires $lesCommentaire): static
    {
        if ($this->lesCommentaires->removeElement($lesCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($lesCommentaire->getLeProduit() === $this) {
                $lesCommentaire->setLeProduit(null);
            }
        }

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
            $lesPromo->setLeProduit($this);
        }

        return $this;
    }

    public function removeLesPromo(Promo $lesPromo): static
    {
        if ($this->lesPromos->removeElement($lesPromo)) {
            // set the owning side to null (unless already changed)
            if ($lesPromo->getLeProduit() === $this) {
                $lesPromo->setLeProduit(null);
            }
        }

        return $this;
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
        }

        return $this;
    }

    public function removeLesImage(Images $lesImage): static
    {
        $this->lesImages->removeElement($lesImage);

        return $this;
    }

    public function getLaCategorie(): ?Categorie
    {
        return $this->laCategorie;
    }

    public function setLaCategorie(?Categorie $laCategorie): static
    {
        $this->laCategorie = $laCategorie;

        return $this;
    }
}

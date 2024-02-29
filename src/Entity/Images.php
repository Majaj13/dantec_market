<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
#[Vich\Uploadable]
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

    #[ORM\ManyToOne(inversedBy: 'lesimages')]
    private ?Actualite $laActualite = null;

     // Assume there's also an imageName property to store the file name
     #[ORM\Column(type: 'string', length: 255)]
     private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'Produits', fileNameProperty: 'imageName')]

    private ?File $imageFile = null;

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
    public function __toString(): string
    {
        return $this->url;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required to at least touch one field to trigger the update
            // if your entity does not use the `updatedAt` field
            
        }

        return $this;
    }
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;
    
        // Mettez à jour l'URL en utilisant le nom du fichier stocké
        $this->url = 'images/' . $imageName;
    
        return $this;
    }
}

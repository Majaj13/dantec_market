<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $classe = null;

    #[ORM\OneToMany(mappedBy: 'leUser', targetEntity: Réserver::class)]
    private Collection $lesReservations;

    #[ORM\OneToMany(mappedBy: 'leUser', targetEntity: Commandes::class)]
    private Collection $lesCommandes;

    #[ORM\OneToMany(mappedBy: 'leUser', targetEntity: Commentaires::class)]
    private Collection $lesCommentaires;

    public function __construct()
    {
        $this->lesReservations = new ArrayCollection();
        $this->lesCommandes = new ArrayCollection();
        $this->lesCommentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): static
    {
        $this->classe = $classe;

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
            $lesReservation->setLeUser($this);
        }

        return $this;
    }

    public function removeLesReservation(Réserver $lesReservation): static
    {
        if ($this->lesReservations->removeElement($lesReservation)) {
            // set the owning side to null (unless already changed)
            if ($lesReservation->getLeUser() === $this) {
                $lesReservation->setLeUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getLesCommandes(): Collection
    {
        return $this->lesCommandes;
    }

    public function addLesCommande(Commandes $lesCommande): static
    {
        if (!$this->lesCommandes->contains($lesCommande)) {
            $this->lesCommandes->add($lesCommande);
            $lesCommande->setLeUser($this);
        }

        return $this;
    }

    public function removeLesCommande(Commandes $lesCommande): static
    {
        if ($this->lesCommandes->removeElement($lesCommande)) {
            // set the owning side to null (unless already changed)
            if ($lesCommande->getLeUser() === $this) {
                $lesCommande->setLeUser(null);
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
            $lesCommentaire->setLeUser($this);
        }

        return $this;
    }

    public function removeLesCommentaire(Commentaires $lesCommentaire): static
    {
        if ($this->lesCommentaires->removeElement($lesCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($lesCommentaire->getLeUser() === $this) {
                $lesCommentaire->setLeUser(null);
            }
        }

        return $this;
    }
}

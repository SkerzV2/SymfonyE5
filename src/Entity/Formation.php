<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $libelle = null;
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column]
    private ?int $nbreHeures = null;

    #[ORM\Column(length: 50)]
    private ?string $departement = null;
    #[ORM\Column(length: 255)]
    private ?string $description = null;
    #[ORM\Column(length: 255)]
    private ?string $image = null;
    #[ORM\OneToMany(targetEntity:Inscription::class,mappedBy:"formation")]
    private Collection $inscription;
    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $produit;

    #[ORM\Column(length: 10)]
    private ?int $nombreInscrit = null;


    public function __construct()
    {
        $this->inscription = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getNbreHeures(): ?int
    {
        return $this->nbreHeures;
    }

    public function setNbreHeures(int $nbreHeures): static
    {
        $this->nbreHeures = $nbreHeures;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscription(): Collection
    {
        return $this->inscription;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscription->contains($inscription)) {
            $this->inscription->add($inscription);
            $inscription->setFormation($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscription->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getFormation() === $this) {
                $inscription->setFormation(null);
            }
        }

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getNombreInscrit(): ?int
    {
        return $this->nombreInscrit;
    }

    public function setNombreInscrit(int $nombreInscrit): static
    {
        $this->nombreInscrit = $nombreInscrit;

        return $this;
    }


}

<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BienRepository::class)
 */
class Bien
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="bien")
     */
    private $operations;

    /**
     * @ORM\OneToOne(targetEntity=Contrat::class, inversedBy="bien", cascade={"persist", "remove"})
     */
    private $contrat;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="biens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity=Localisation::class, inversedBy="biens")
     */
    private $localisation;

    /**
     * @ORM\OneToOne(targetEntity=Maison::class, mappedBy="bien", cascade={"persist", "remove"})
     */
    private $maison;

    /**
     * @ORM\OneToOne(targetEntity=Appartement::class, mappedBy="bien", cascade={"persist", "remove"})
     */
    private $appartement;

    /**
     * @ORM\OneToOne(targetEntity=Terrain::class, mappedBy="bien", cascade={"persist", "remove"})
     */
    private $terrain;

    /**
     * @ORM\OneToOne(targetEntity=Studio::class, mappedBy="bien", cascade={"persist", "remove"})
     */
    private $studio;

    /**
     * @ORM\ManyToOne(targetEntity=Proprietaire::class, inversedBy="biens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $besoin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    public function __construct()
    {
        $this->operations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setBien($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getBien() === $this) {
                $operation->setBien(null);
            }
        }

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getAgent(): ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getMaison(): ?Maison
    {
        return $this->maison;
    }

    public function setMaison(?Maison $maison): self
    {
        // unset the owning side of the relation if necessary
        if ($maison === null && $this->maison !== null) {
            $this->maison->setBien(null);
        }

        // set the owning side of the relation if necessary
        if ($maison !== null && $maison->getBien() !== $this) {
            $maison->setBien($this);
        }

        $this->maison = $maison;

        return $this;
    }

    public function getAppartement(): ?Appartement
    {
        return $this->appartement;
    }

    public function setAppartement(Appartement $appartement): self
    {
        // set the owning side of the relation if necessary
        if ($appartement->getBien() !== $this) {
            $appartement->setBien($this);
        }

        $this->appartement = $appartement;

        return $this;
    }

    public function getTerrain(): ?Terrain
    {
        return $this->terrain;
    }

    public function setTerrain(Terrain $terrain): self
    {
        // set the owning side of the relation if necessary
        if ($terrain->getBien() !== $this) {
            $terrain->setBien($this);
        }

        $this->terrain = $terrain;

        return $this;
    }

    public function getStudio(): ?Studio
    {
        return $this->studio;
    }

    public function setStudio(Studio $studio): self
    {
        // set the owning side of the relation if necessary
        if ($studio->getBien() !== $this) {
            $studio->setBien($this);
        }

        $this->studio = $studio;

        return $this;
    }

    public function getProprietaire(): ?Proprietaire
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Proprietaire $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getBesoin(): ?string
    {
        return $this->besoin;
    }

    public function setBesoin(string $besoin): self
    {
        $this->besoin = $besoin;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}

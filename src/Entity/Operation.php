<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="operations")
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity=Contrat::class, mappedBy="operation", cascade={"persist", "remove"})
     */
    private $contrat;

    /**
     * @ORM\ManyToOne(targetEntity=Bien::class, inversedBy="operations")
     */
    private $bien;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="operation")
     */
    private $factures;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="operations")
     */
    private $agent;


    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        // unset the owning side of the relation if necessary
        if ($contrat === null && $this->contrat !== null) {
            $this->contrat->setOperation(null);
        }

        // set the owning side of the relation if necessary
        if ($contrat !== null && $contrat->getOperation() !== $this) {
            $contrat->setOperation($this);
        }

        $this->contrat = $contrat;

        return $this;
    }

    public function getBien(): ?Bien
    {
        return $this->bien;
    }

    public function setBien(?Bien $bien): self
    {
        $this->bien = $bien;

        return $this;
    }

    /**
     * @return Collection|Operation[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFactures(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setOperation($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getOperation() === $this) {
                $facture->setOperation(null);
            }
        }

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
}

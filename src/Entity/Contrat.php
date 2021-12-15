<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratRepository::class)
 */
class Contrat
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
    private $reference;

    /**
     * @ORM\OneToOne(targetEntity=Operation::class, inversedBy="contrat", cascade={"persist", "remove"})
     */
    private $operation;

    /**
     * @ORM\OneToOne(targetEntity=Bien::class, mappedBy="contrat", cascade={"persist", "remove"})
     */
    private $bien;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getBien(): ?Bien
    {
        return $this->bien;
    }

    public function setBien(?Bien $bien): self
    {
        // unset the owning side of the relation if necessary
        if ($bien === null && $this->bien !== null) {
            $this->bien->setContrat(null);
        }

        // set the owning side of the relation if necessary
        if ($bien !== null && $bien->getContrat() !== $this) {
            $bien->setContrat($this);
        }

        $this->bien = $bien;

        return $this;
    }
}

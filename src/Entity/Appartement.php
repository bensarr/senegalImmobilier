<?php

namespace App\Entity;

use App\Repository\AppartementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppartementRepository::class)
 */
class Appartement
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
    private $typeBail;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $montantCaution;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $loyerMensuel;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrChambre;

    /**
     * @ORM\OneToOne(targetEntity=Bien::class, inversedBy="appartement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $bien;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeBail(): ?string
    {
        return $this->typeBail;
    }

    public function setTypeBail(string $typeBail): self
    {
        $this->typeBail = $typeBail;

        return $this;
    }

    public function getMontantCaution(): ?string
    {
        return $this->montantCaution;
    }

    public function setMontantCaution(string $montantCaution): self
    {
        $this->montantCaution = $montantCaution;

        return $this;
    }

    public function getLoyerMensuel(): ?string
    {
        return $this->loyerMensuel;
    }

    public function setLoyerMensuel(string $loyerMensuel): self
    {
        $this->loyerMensuel = $loyerMensuel;

        return $this;
    }

    public function getNbrChambre(): ?int
    {
        return $this->nbrChambre;
    }

    public function setNbrChambre(int $nbrChambre): self
    {
        $this->nbrChambre = $nbrChambre;

        return $this;
    }

    public function getBien(): ?Bien
    {
        return $this->bien;
    }

    public function setBien(Bien $bien): self
    {
        $this->bien = $bien;

        return $this;
    }
}

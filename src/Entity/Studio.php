<?php

namespace App\Entity;

use App\Repository\StudioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudioRepository::class)
 */
class Studio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $montantCaution;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $loyerMensuel;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $superficie;

    /**
     * @ORM\OneToOne(targetEntity=Bien::class, inversedBy="studio", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $bien;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSuperficie(): ?string
    {
        return $this->superficie;
    }

    public function setSuperficie(string $superficie): self
    {
        $this->superficie = $superficie;

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

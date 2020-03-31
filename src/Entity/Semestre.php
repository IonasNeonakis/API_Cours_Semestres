<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SemestreRepository")
 */
class Semestre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom_Formation;

    /**
     * @ORM\Column(type="integer")
     */
    private $Numero_Semestre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cours", mappedBy="semestre", orphanRemoval=true)
     */
    private $Cours;

    public function __construct()
    {
        $this->Cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormation(): ?string
    {
        return $this->Nom_Formation;
    }

    public function setNomFormation(string $Nom_Formation): self
    {
        $this->Nom_Formation = $Nom_Formation;

        return $this;
    }

    public function getNumeroSemestre(): ?int
    {
        return $this->Numero_Semestre;
    }

    public function setNumeroSemestre(int $Numero_Semestre): self
    {
        $this->Numero_Semestre = $Numero_Semestre;

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->Cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->Cours->contains($cour)) {
            $this->Cours[] = $cour;
            $cour->setSemestre($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->Cours->contains($cour)) {
            $this->Cours->removeElement($cour);
            // set the owning side to null (unless already changed)
            if ($cour->getSemestre() === $this) {
                $cour->setSemestre(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InformationPageTarifRepository")
 */
class InformationPageTarif
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $tarif;

    /**
     * @ORM\Column(type="integer")
     */
    private $seanceEssai;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarif(): ?int
    {
        return $this->tarif;
    }

    public function setTarif(int $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getSeanceEssai(): ?int
    {
        return $this->seanceEssai;
    }

    public function setSeanceEssai(int $seanceEssai): self
    {
        $this->seanceEssai = $seanceEssai;

        return $this;
    }
}

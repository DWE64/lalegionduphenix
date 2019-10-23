<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FichierPageTarifRepository")
 */
class FichierPageTarif
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
    private $nomAffichage;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomFichier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(string $nomFichier): self
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }
    public function getNomAffichage(): ?string
    {
        return $this->nomAffichage;
    }
    public function setNomAffichage($nomAffichage){
        $this->nomAffichage=$nomAffichage;
        return $this;
    }
    
}

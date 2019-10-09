<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JeuRepository")
 */
class Jeu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imgJeu;
    
    private $image;
    
    
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id=$id;
        
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    
    public function getImage()
    {
        return $this->image;
    }
    
    
    
    public function getImgJeu(): ?string
    {
        return $this->imgJeu;
    }
    public function setImgJeu(string $img_jeu): self
    {
        $this->imgJeu = $img_jeu;
        return $this;
    }
    
    /**
     *
     * @param UploadedFile $Image
     * @return \App\Entity\Article
     */
    public function setImage(UploadedFile $Image=null)
    {
        $this->image=$Image;
        
    }
}

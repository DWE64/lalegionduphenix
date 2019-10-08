<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvenementParticiperRepository")
 */
class EvenementParticiper
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
    private $img_event_part;
    
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
    
    
    
    public function getImgEventPart(): ?string
    {
        return $this->img_event_part;
    }
    public function setImgEventPart(string $img_event_part): self
    {
        $this->img_event_part = $img_event_part;
        return $this;
    }
    
    /**
     *
     * @param UploadedFile $Image
     * @return \App\Entity\EvenementParticiper
     */
    public function setImage(UploadedFile $Image=null)
    {
        $this->image=$Image;
        
    }
}

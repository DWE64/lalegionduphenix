<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use phpDocumentor\Reflection\DocBlock\Tags\Property;

class Contact {
    
    /**
     * 
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=255)
     */
    private $nomUtilisateur;
    
    /**
     *
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=255)
     */
    private $prenomUtilisateur;
    
    /**
     *
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/[0-9]{10}/")
     * 
     */
    private $telephone;
    
    /**
     *
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     */
    private $mail;
    
    
    /**
     *
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=255)
     */
    private $sujet;
    
    /**
     *
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     *
     */
    private $message;
    
    
    
    
    /**
     * @return string, NULL
     */
    public function getNomUtilisateur()
    {
        return $this->nomUtilisateur;
    }

    /**
     * @return string, NULL
     */
    public function getPrenomUtilisateur()
    {
        return $this->prenomUtilisateur;
    }

    /**
     * @return string, NULL
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @return string, NULL
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @return string, NULL
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string, NULL $nomUtilisateur
     */
    public function setNomUtilisateur($nomUtilisateur)
    {
        $this->nomUtilisateur = $nomUtilisateur;
    }

    /**
     * @param string, NULL $prenomUtilisateur
     */
    public function setPrenomUtilisateur($prenomUtilisateur)
    {
        $this->prenomUtilisateur = $prenomUtilisateur;
    }

    /**
     * @param string, NULL $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @param string, NULL $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @param string, NULL $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
    /**
     * @return string, NULL
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * @param string, NULL> $suje
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    }

    


}


?>
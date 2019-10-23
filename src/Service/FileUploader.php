<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $repertoireCible;
    
    public function __construct($repertoireCible){
        $this->repertoireCible=$repertoireCible;
    }
    
    public function uploadFichier(UploadedFile $fichier){
        
        $nomFichierOrigine=pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
        $enregistrementNomFichier=transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $nomFichierOrigine);
        $nouveauNomFichier=$enregistrementNomFichier.'-'.uniqid().'.'.$fichier->guessExtension();
        
        try{
            $fichier->move($this->getRepertoireCible(), $nouveauNomFichier);
        }catch(FileException $e){
            print_r($e);
        }
        return $nouveauNomFichier;
    }
    
    public function getRepertoireCible(){
        return $this->repertoireCible;
    }
    
    
}


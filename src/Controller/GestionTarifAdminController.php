<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\FichierPageTarif;
use App\Repository\FichierPageTarifRepository;
use App\Repository\InformationPageTarifRepository;
use App\Entity\InformationPageTarif;
use App\Form\formulaireInfoTarifType;
use App\Form\formulaireFichierUploadType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;



class GestionTarifAdminController extends AbstractController
{
    /**
     * @Route("/admin/listeInformationTarif", name="listeInfoTarifs")
     */
    public function indexAdmin(FichierPageTarifRepository $fichierRepository, InformationPageTarifRepository $information, 
        EntityManagerInterface $em, Request $request, FileUploader $fichierUploader)
    {
      // fonction permettant l'affichage des articles ET la création d'un nouvel article
       $hasAccess=$this->isGranted('ROLE_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       /*
        * formulaire d'ajout d'information
        */
       $objetInformationTarif=new InformationPageTarif();       
       $formulaireInfo=$this->createForm(formulaireInfoTarifType::class, $objetInformationTarif);
       $formulaireInfo->handleRequest($request);
       
       
       if($formulaireInfo->isSubmitted() && $formulaireInfo->isValid()){
           
           // action : 
           $em->persist($objetInformationTarif);
          //envoi à la bdd
           $em->flush();
           //message flash
           $type='info';
           $message='Information tarif ajoutée';
           $this->addFlash($type, $message);
           
           return $this->redirectToRoute("listeInfoTarifs");
       }
       
       
       
       /*
        * formulaire d'ajout de fichier pdf
        */
       $objetFichierUpload=new FichierPageTarif();
       $formulaireUpload=$this->createForm(formulaireFichierUploadType::class, $objetFichierUpload);
       $formulaireUpload->handleRequest($request);
       
       if($formulaireUpload->isSubmitted() && $formulaireUpload->isValid()){

           /**
            * @var UploadedFile $fichier
            */
           
            if($formulaireUpload['nomFichier']->getData()!=null){
              $fichierUpload=$formulaireUpload['nomFichier']->getData();
              
              if($fichierUpload){
                  
                  $newFilename=$fichierUploader->uploadFichier($fichierUpload);
                  $objetFichierUpload->setNomFichier($newFilename);
                  
              }
              $objetFichierUpload->setNomAffichage($formulaireUpload['nomAffichage']->getData());
           }
           $em->persist($objetFichierUpload);
           $em->flush();
           
           $type='info';
           $message='Nouveau fichier stocké';
           $this->addFlash($type, $message);
           
           return $this->redirectToRoute("listeInfoTarifs");
       }
      return $this->render('admin/pageTarif/newInfoTarif.html.twig', [
          'tableauObjetInformation'=>$information->findAll(),
          'tableauObjetFichierUpload'=>$fichierRepository->findAll(),
          'formulaireInformation'=>$formulaireInfo->createView(),
          'formulaireUpload' => $formulaireUpload->createView(),
        ]);
     }
       
      
     /**
      * @Route("/admin/Information/{id}/delete", name="delete_information")
      */
     public function deleteInformation(InformationPageTarif $info, EntityManagerInterface $em){ //ici la fonction récupère l'id de l'article renseignée dans la route appelée dans le fichier twig
         
        //on supprime l'objet article passé en paramètre
         $em->remove($info);
         //on dit a la bdd d'effacer l'article en question de la base
         $em->flush();
         // on affiche le message de confirmation
         $type='info';
         $message='information supprimé';
         $this->addFlash($type, $message);
         
         //on retourne à la liste
         return $this->redirectToRoute('listeInfoTarifs');
     }
     /**
      * @Route("/admin/fichier/{id}/delete", name="delete_fichier")
      */
     public function deleteFichier(FichierPageTarif $fichier, EntityManagerInterface $em){ //ici la fonction récupère l'id de l'article renseignée dans la route appelée dans le fichier twig
         
         //on efface le lien d'image s'il y a une image puis on supprime l'image du dossier
         
         if($fichier->getNomFichier()!=null && $fichier->getNomFichier()!=''){
             unlink($this->getParameter('fichier_upload').'/'.$fichier->getNomFichier());
         }
         
         //on supprime l'objet article passé en paramètre
         $em->remove($fichier);
         //on dit a la bdd d'effacer l'article en question de la base
         $em->flush();
         // on affiche le message de confirmation
         $type='info';
         $message='fichier supprimé';
         $this->addFlash($type, $message);
         
         //on retourne à la liste
         return $this->redirectToRoute('listeInfoTarifs');
     }
    
     
     
    /**
     * @Route("/admin/Information/{id}/edition", name="edit_information")
     */
    public function updateInformation(InformationPageTarif $info, EntityManagerInterface $em, Request $request){ //ici on récupère l'id passé en paramètre et la réponse au formulaire de modification
        
        //on crée le formulaire de modif a partir du même formulaire que celui de création d'article
        $formulaireModifInfo=$this->createForm(formulaireInfoTarifType::class, $info);
        $formulaireModifInfo->handleRequest($request);
        
        if($formulaireModifInfo->isSubmitted() && $formulaireModifInfo->isValid()){
            // on check si l'image est modifiée. si oui, on supprime le lien de l'ancienne, on supprime l'image du dossier, et on enregistre la nouvelle
            
            $em->flush();
        
            return $this->redirectToRoute('listeInfoTarifs');
        }
        return $this->render('admin/pageTarif/editInfoTarif.html.twig',[
            'formulaireEdit'=>$formulaireModifInfo->createView(),
            'idInformation'=>$info->getId(),
        ]);
    }
    
    
    
    
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
    
}



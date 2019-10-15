<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Form\formulaireArticleType;
use App\Form\formulaireLieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\Length;
use App\Repository\ArticleRepository;
use App\Repository\LieuRepository;
use App\Entity\Lieu;



class GestionContactAdminController extends AbstractController
{
    /**
     * @Route("/admin/contact/listeLieu", name="listeLieu")
     */
    public function indexAdmin(LieuRepository $lieu, EntityManagerInterface $em, Request $request)
    {
      // fonction permettant l'affichage des articles ET la cr�ation d'un nouvel article
       $hasAccess=$this->isGranted('ROLE_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       
       $objetLieu=new Lieu();
       
       $formulaireLieu=$this->createForm(formulaireLieuType::class, $objetLieu);
       $formulaireLieu->handleRequest($request);
       
       if($formulaireLieu->isSubmitted() && $formulaireLieu->isValid()){
           
           //ici on r�cup�re et on stock l'image dans le dossier public correspondant au service renseign� dans le service.yaml
           if($formulaireLieu['image']->getData()!=null){
               $file=$formulaireLieu['image']->getData();
               $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
               
               try{
                   $file->move($this->getParameter('image_lieu'), $filename);
                   
               }catch(FileException $e){
                   return $e;
               }
               $objetLieu->setImage($formulaireLieu['image']->getData());
               $objetLieu->setImgLieu($filename);
           }
           
           // action : 
           $em->persist($objetLieu);
           
           //envoi � la bdd
           
           $em->flush();
           
           //message flash
           $type='info';
           $message='nouveau lieu cr��';
           $this->addFlash($type, $message);
           
           return $this->redirectToRoute("listeLieu");
       }
      return $this->render('admin/contact/newLieu.html.twig', [
          'tableauObjetLieu'=>$lieu->findAll(),
          'formulaireLieu'=>$formulaireLieu->createView(),
        ]);
     }
       
      
     /**
      * @Route("/admin/contact/{id}/delete", name="delete_lieu")
      */
     public function deleteLieu(Lieu $lieu, EntityManagerInterface $em){ //ici la fonction r�cup�re l'id de l'article renseign�e dans la route appel�e dans le fichier twig
         
         //on efface le lien d'image s'il y a une image puis on supprime l'image du dossier
         
         if($lieu->getImgLieu()!=null && $lieu->getImgLieu()!=''){
             unlink($this->getParameter('image_lieu').'/'.$lieu->getImgLieu());
         }      
                  
         //on supprime l'objet article pass� en param�tre
         $em->remove($lieu);
         //on dit a la bdd d'effacer l'article en question de la base
         $em->flush();
         // on affiche le message de confirmation
         $type='info';
         $message='lieu supprim�';
         $this->addFlash($type, $message);
         
         //on retourne � la liste
         return $this->redirectToRoute('listeLieu');
     }
    
    
    /**
     * @Route("/admin/contact/{id}/edition", name="edit_lieu")
     */
    public function updateLieu(Lieu $lieu, EntityManagerInterface $em, Request $request){ //ici on r�cup�re l'id pass� en param�tre et la r�ponse au formulaire de modification
        
        //on cr�e le formulaire de modif a partir du m�me formulaire que celui de cr�ation d'article
        $formulaireModif=$this->createForm(formulaireLieuType::class, $lieu);
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            // on check si l'image est modifi�e. si oui, on supprime le lien de l'ancienne, on supprime l'image du dossier, et on enregistre la nouvelle
            if($formulaireModif['image']->getData()!=null){
                if($lieu->getImgLieu()!=null && $lieu->getImgLieu()!=''){
                    unlink($this->getParameter('image_lieu').'/'.$lieu->getImgLieu());
                }
                $file=$formulaireModif['image']->getData();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_lieu'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                $lieu->setImage($formulaireModif['image']->getData());
                $lieu->setImgLieu($filename);
                
            }
            $em->flush();
        
            return $this->redirectToRoute('listeLieu');
        }
        return $this->render('admin/contact/editLieu.html.twig',[
            'formulaireEdit'=>$formulaireModif->createView(),
            'idLieu'=>$lieu->getId(),
        ]);
    }
    
    
    /**
     * @Route("admin/contact/{id}", name="visualisation_lieu")
     */
   
    public function viewLieu(Lieu $lieu, $id){
        
        return $this->render('admin/contact/viewLieu.html.twig', [
           'lieu'=>$lieu 
        ]);
        
    }
    
    
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
    
}



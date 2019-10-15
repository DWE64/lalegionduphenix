<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\Jeu;
use App\Form\formulaireJeuType;
use App\Repository\JeuRepository;


class GestionJeuAdminController extends AbstractController
{
    /**
     * @Route("/admin/gestionJeu/listeJeu", name="listage_jeu")
     * 
     */
    public function indexAdmin(JeuRepository $jeuRepository, EntityManagerInterface $em, Request $request)
    {
       
        $hasAccess=$this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $objetJeu=new Jeu();
        
        $formulaireJeu=$this->createForm(formulaireJeuType::class, $objetJeu);
        $formulaireJeu->handleRequest($request);
        
        if($formulaireJeu->isSubmitted() && $formulaireJeu->isValid()){
            
            //ici on r�cup�re et on stock l'image dans le dossier public correspondant au service renseign� dans le service.yaml
            if($formulaireJeu['image']->getData()!=null){
                $file=$formulaireJeu['image']->getData();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_jeu'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                $objetJeu->setImage($formulaireJeu['image']->getData());
                $objetJeu->setImgJeu($filename);
            }
            
            // action :
            $em->persist($objetJeu);
            
            //envoi � la bdd
            
            $em->flush();
            
            //message flash
            $type='info';
            $message='nouveau jeu cr��';
            $this->addFlash($type, $message);
            
            return $this->redirectToRoute("listage_jeu");
        }
        return $this->render('admin/jeu/newJeu.html.twig', [
            'tableauObjetJeu'=>$jeuRepository->findAll(),
            'formulaireJeu'=>$formulaireJeu->createView(),
        ]);
    }
    /**
     * @Route("/admin/gestionJeu/Jeu/{id}/delete", name="delete_jeu")
     */
    public function deleteJeu(Jeu $jeu, EntityManagerInterface $em){ //ici la fonction r�cup�re l'id de l'article renseign�e dans la route appel�e dans le fichier twig
        
        //on efface le lien d'image s'il y a une image puis on supprime l'image du dossier
        
        if($jeu->getImgJeu()!=null && $jeu->getImgJeu()!=''){
            unlink($this->getParameter('image_jeu').'/'.$jeu->getImgJeu());
        }
        
        //on supprime l'objet article pass� en param�tre
        $em->remove($jeu);
        //on dit a la bdd d'effacer l'article en question de la base
        $em->flush();
        // on affiche le message de confirmation
        $type='info';
        $message='article supprim�';
        $this->addFlash($type, $message);
        
        //on retourne � la liste
        return $this->redirectToRoute('listage_jeu');
    }
    
    
    /**
     * @Route("/admin/gestionJeu/Jeu/{id}/edition", name="edit_jeu")
     */
    public function updateJeu(Jeu $jeu, EntityManagerInterface $em, Request $request){ //ici on r�cup�re l'id pass� en param�tre et la r�ponse au formulaire de modification
        
        //on cr�e le formulaire de modif a partir du m�me formulaire que celui de cr�ation d'article
        $formulaireModif=$this->createForm(formulaireJeuType::class, $jeu);
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            // on check si l'image est modifi�e. si oui, on supprime le lien de l'ancienne, on supprime l'image du dossier, et on enregistre la nouvelle
            if($formulaireModif['image']->getData()!=null){
                if($jeu->getImgJeu()!=null && $jeu->getImgJeu()!=''){
                    unlink($this->getParameter('image_jeu').'/'.$jeu->getImgJeu());
                }
                $file=$formulaireModif['image']->getData();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_jeu'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                $jeu->setImage($formulaireModif['image']->getData());
                $jeu->setImgJeu($filename);
                
            }
            $em->flush();
            
            return $this->redirectToRoute('listage_jeu');
        }
        return $this->render('admin/jeu/editJeu.html.twig',[
            'formulaireEdit'=>$formulaireModif->createView(),
            'idJeu'=>$jeu->getId(),
        ]);
    }
    
    
    /**
     * @Route("admin/gestionJeu/Jeu/{id}", name="visualisation_jeu")
     */
    
    public function viewJeu(Jeu $jeu, $id){
        
        return $this->render('admin/jeu/viewJeu.html.twig', [
            'jeu'=>$jeu
        ]);
        
    }
    
    
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
    
}



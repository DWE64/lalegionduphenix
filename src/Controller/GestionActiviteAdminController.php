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
use App\Form\ModificationJeu;
use App\Form\ModificationRoleUser;
use App\Form\SuppressionJeu;
use App\Entity\Jeu;
use App\Form\formulaireJeuType;


class GestionActiviteAdminController extends AbstractController
{
    /**
     * @Route("/admin/gestionActivite", name="gestionActivite")
     */
    public function indexAdmin(Request $request):Response
    {
        // necessaire pour faire le tri sur l'autorisation de connexion. chercher aussi pour SUPER_ADMIN
       $hasAccess=$this->isGranted('ROLE_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       //affichage des différentes activités
       $listeJeu=GestionActiviteAdminController::listeJeu();
       
       //formulaire d'ajout d'activité
       
       $jeu=new Jeu();
       $formNewJeu=$this->createForm(formulaireJeuType::class, $jeu,[
           'action'=>$this->generateUrl('ajout_jeu'),
           'method'=>'POST',
       ]);
       
       //formulaire de modification d'activité
       
       
      $formModifJeu=$this->createForm(ModificationJeu::class, $jeu,[
           'action'=>$this->generateUrl('update_jeu'),
           'method'=>'POST',
       ]);
       
              
       //formulaire de suppression d'activité
       
       
       $formSupJeu=$this->createForm(SuppressionJeu::class, $jeu,[
           'action'=>$this->generateUrl('delete_jeu'),
           'method'=>'POST',
       ]);
       
       return  $this->render('admin/gestionActiviteAdmin.html.twig',[
            "listeJeu"=>$listeJeu,
            "formNewJeu"=>$formNewJeu->createView(),
            "formModifJeu"=>$formModifJeu->createView(),
            "formSup"=>$formSupJeu->createView(),
        ]);
    }
    
    
    
    
    /**
     * 
     * @Route("/admin/listeJeu", name="listage_Jeu")
     */
    
    public function listeJeu(){
          $jeu=$this->getDoctrine()->getRepository(Jeu::class)->findAll();
                    
          return $jeu;
    }
    
    /**
     * @Route("/admin/ajoutJeu", name="ajout_jeu")
     */
    public function newGame(Request $request){
        $newGame=new Jeu();
        $formulaireModif=$this->createForm(formulaireJeuType::class, $newGame);
        
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            if($newGame->getImage()!=null){
                $file=$newGame->getImage();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_jeu'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                
                $newGame->setImgJeu($filename);
            }
            
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($newGame);
            $entityManager->flush();
            
            
            return $this->redirectToRoute('gestionActivite');
            
        }
        return $this->redirectToRoute('gestionActivite');
    }
    
    /**
     * @Route("/admin/modifJeu", name="update_jeu")
     */
    public function updateGame(Request $request){
        $dataModifJeu=new jeu();
        $formulaireModif=$this->createForm(ModificationJeu::class, $dataModifJeu);
        
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
        $entityManager=$this->getDoctrine()->getManager();
        $jeu=$entityManager->getRepository(Jeu::class)->find($dataModifJeu->getId());
        
        if(!$jeu){
            throw $this->createNotFoundException('aucun jeu a modifier avec cet identifiant: '.$dataModifJeu->getId());
        }
        
        if($dataModifJeu->gettitre()!='' && $dataModifJeu->getTitre()!=null){
            $jeu->setTitre($dataModifJeu->getTitre());
        }
        if($dataModifJeu->getDescription()!='' && $dataModifJeu->getDescription()!=null){
            $jeu->setDescription($dataModifJeu->getDescription());
        }
        if($dataModifJeu->getImage()!=null){
            if($jeu->getImgJeu()!=null && $jeu->getImgJeu()!=''){
                unlink($this->getParameter('image_jeu').'/'.$jeu->getImgJeu());
            }
            $file=$dataModifJeu->getImage();
            $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
            
            try{
                $file->move($this->getParameter('image_jeu'), $filename);
                
            }catch(FileException $e){
                return $e;
            }
            
            $jeu->setImgJeu($filename);
            
        }
                
        $entityManager->flush();
        
        return $this->redirectToRoute('gestionActivite');
        }
        return $this->redirectToRoute('gestionActivite');
    }
    
    /**
     * @Route("/admin/supprimerJeu", name="delete_jeu")
     */
    public function deleteGame(Request $request){
        $supJeu=new Jeu();
        $formulaireSup=$this->createForm(SuppressionJeu::class, $supJeu);
        $formulaireSup->handleRequest($request);
        
        
        if($formulaireSup->isSubmitted() && $formulaireSup->isValid()){
            $entityManager=$this->getDoctrine()->getManager();
            $jeu=$entityManager->getRepository(Jeu::class)->find($supJeu->getId());
            if(!$jeu){
                throw $this->createNotFoundException('aucun jeu a supprimer avec cet identifiant: '.$supJeu->getId());
            }
            unlink($this->getParameter('image_jeu').'/'.$jeu->getImgJeu());
            $entityManager->remove($jeu);
            $entityManager->flush();
            
            return $this->redirectToRoute('gestionActivite');
        }
        return $this->redirectToRoute('gestionActivite');
        
    }
    
    
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
    
}



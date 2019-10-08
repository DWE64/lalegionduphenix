<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\EvenementParticiper;
use App\Form\ModificationEventPart;
use App\Form\SuppressionEventPart;
use App\Form\formulaireEventPartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\Length;


class GestionEvenementParticiperAdminController extends AbstractController
{
    /**
     * @Route("/admin/gestionEvenementParticiper", name="gestionEvenementParticiper")
     */
    public function indexAdmin(Request $request):Response
    {
        // necessaire pour faire le tri sur l'autorisation de connexion. chercher aussi pour SUPER_ADMIN
       $hasAccess=$this->isGranted('ROLE_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       //affichage des différentes activités
       $listeEventPart=GestionEvenementParticiperAdminController::listeEventPart();
       
       //formulaire d'ajout d'activité
       
       $eventPart=new EvenementParticiper();
       $formNewEventPart=$this->createForm(formulaireEventPartType::class, $eventPart,[
           'action'=>$this->generateUrl('ajout_event_part'),
           'method'=>'POST',
       ]);
       
       //formulaire de modification d'activité
       
       
      $formModifEventPart=$this->createForm(ModificationEventPart::class, $eventPart,[
           'action'=>$this->generateUrl('update_event_part'),
           'method'=>'POST',
       ]);
       
              
       //formulaire de suppression d'activité
       
       
       $formSupEventPart=$this->createForm(SuppressionEventPart::class, $eventPart,[
           'action'=>$this->generateUrl('delete_event_part'),
           'method'=>'POST',
       ]);
       
       return  $this->render('admin/gestionEvenementParticiperAdmin.html.twig',[
            "listeEventPart"=>$listeEventPart,
            "formNewEventPart"=>$formNewEventPart->createView(),
            "formModifEventPart"=>$formModifEventPart->createView(),
            "formSupEventPart"=>$formSupEventPart->createView(),
        ]);
    }
    
    
    
    
    /**
     * 
     * @Route("/admin/listeEventPart", name="listage_event_part")
     */
    
    public function listeEventPart(){
          $eventPart=$this->getDoctrine()->getRepository(EvenementParticiper::class)->findAll();
                    
          return $eventPart;
    }
    
    /**
     * @Route("/admin/ajoutEventPart", name="ajout_event_part")
     */
    public function newEventPart(Request $request){
        $newEventPart=new EvenementParticiper();
        $formulaireModif=$this->createForm(formulaireEventPartType::class, $newEventPart);
        
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            if($newEventPart->getImage()!=null){
                $file=$newEventPart->getImage();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_event_part'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                
                $newEventPart->setImgEventPart($filename);
            }
            
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($newEventPart);
            $entityManager->flush();
            
            
            return $this->redirectToRoute('gestionEvenementParticiper');
            
        }
        return $this->redirectToRoute('gestionEvenementParticiper');
    }
    
    /**
     * @Route("/admin/modifEventPart", name="update_event_part")
     */
    public function updateEventPart(Request $request){
        $dataModifEventPart=new EvenementParticiper();
        $formulaireModif=$this->createForm(ModificationEventPart::class, $dataModifEventPart);
        
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
        $entityManager=$this->getDoctrine()->getManager();
        $eventPart=$entityManager->getRepository(EvenementParticiper::class)->find($dataModifEventPart->getId());
        
        if(!$eventPart){
            throw $this->createNotFoundException('aucun evenement a modifier avec cet identifiant: '.$dataModifEventPart->getId());
        }
        
        if($dataModifEventPart->gettitre()!='' && $dataModifEventPart->getTitre()!=null){
            $eventPart->setTitre($dataModifEventPart->getTitre());
        }
        if($dataModifEventPart->getDescription()!='' && $dataModifEventPart->getDescription()!=null){
            $eventPart->setDescription($dataModifEventPart->getDescription());
        }
        if($dataModifEventPart->getImage()!=null){
            if($eventPart->getImgEventPart()!=null && $eventPart->getImgEventPart()!=''){
                unlink($this->getParameter('image_event_part').'/'.$eventPart->getImgEventPart());
            }
            $file=$dataModifEventPart->getImage();
            $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
            
            try{
                $file->move($this->getParameter('image_event_part'), $filename);
                
            }catch(FileException $e){
                return $e;
            }
            
            $eventPart->setImgEventPart($filename);
            
        }
                
        $entityManager->flush();
        
        return $this->redirectToRoute('gestionEvenementParticiper');
        }
        return $this->redirectToRoute('gestionEvenementParticiper');
    }
    
    /**
     * @Route("/admin/supprimerEventPart", name="delete_event_part")
     */
    public function deleteEventPart(Request $request){
        $supEventPart=new EvenementParticiper();
        $formulaireSup=$this->createForm(SuppressionEventPart::class, $supEventPart);
        $formulaireSup->handleRequest($request);
        
        
        if($formulaireSup->isSubmitted() && $formulaireSup->isValid()){
            $entityManager=$this->getDoctrine()->getManager();
            $event=$entityManager->getRepository(EvenementParticiper::class)->find($supEventPart->getId());
            if(!$event){
                throw $this->createNotFoundException('aucun evenement a supprimer avec cet identifiant: '.$supEventPart->getId());
            }
            unlink($this->getParameter('image_event_part').'/'.$event->getImgEventPart());
            $entityManager->remove($event);
            $entityManager->flush();
            
            return $this->redirectToRoute('gestionEvenementParticiper');
        }
        return $this->redirectToRoute('gestionEvenementParticiper');
        
    }
    
    
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
    
}



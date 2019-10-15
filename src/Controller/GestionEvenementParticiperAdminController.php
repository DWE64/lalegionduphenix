<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\EvenementParticiper;
use App\Form\formulaireEventPartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\Length;
use App\Repository\EvenementParticiperRepository;


class GestionEvenementParticiperAdminController extends AbstractController
{
    /**
     * @Route("/admin/gestionEventExterieur/listeEventPart", name="listage_eventPart")
     *
     */
    public function indexAdmin(EvenementParticiperRepository $EventRepository, EntityManagerInterface $em, Request $request)
    {
        
        $hasAccess=$this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $objetEventPart=new EvenementParticiper();
        
        $formulaireEventPart=$this->createForm(formulaireEventPartType::class, $objetEventPart);
        $formulaireEventPart->handleRequest($request);
        
        if($formulaireEventPart->isSubmitted() && $formulaireEventPart->isValid()){
            
            //ici on récupère et on stock l'image dans le dossier public correspondant au service renseigné dans le service.yaml
            if($formulaireEventPart['image']->getData()!=null){
                $file=$formulaireEventPart['image']->getData();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_event_part'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                $objetEventPart->setImage($formulaireEventPart['image']->getData());
                $objetEventPart->setImgEventPart($filename);
            }
            
            // action :
            $em->persist($objetEventPart);
            
            //envoi à la bdd
            
            $em->flush();
            
            //message flash
            $type='info';
            $message='nouvel evenement extérieur créé';
            $this->addFlash($type, $message);
            
            return $this->redirectToRoute("listage_eventPart");
        }
        return $this->render('admin/eventParticiper/newEventPart.html.twig', [
            'tableauObjetEvent'=>$EventRepository->findAll(),
            'formulaireEventPart'=>$formulaireEventPart->createView(),
        ]);
    }
    /**
     * @Route("/admin/gestionEventExterieur/eventPart/{id}/delete", name="delete_eventPart")
     */
    public function deleteEvent(EvenementParticiper $event, EntityManagerInterface $em){ //ici la fonction récupère l'id de l'article renseignée dans la route appelée dans le fichier twig
        
        //on efface le lien d'image s'il y a une image puis on supprime l'image du dossier
        
        if($event->getImgEventPart()!=null && $event->getImgEventPart()!=''){
            unlink($this->getParameter('image_event_part').'/'.$event->getImgEventPart());
        }
        
        //on supprime l'objet article passé en paramètre
        $em->remove($event);
        //on dit a la bdd d'effacer l'article en question de la base
        $em->flush();
        // on affiche le message de confirmation
        $type='info';
        $message='evenement supprimé';
        $this->addFlash($type, $message);
        
        //on retourne à la liste
        return $this->redirectToRoute('listage_eventPart');
    }
    
    
    /**
     * @Route("/admin/gestionEventExterieur/eventPart/{id}/edition", name="edit_eventPart")
     */
    public function updateEvent(EvenementParticiper $event, EntityManagerInterface $em, Request $request){ //ici on récupère l'id passé en paramètre et la réponse au formulaire de modification
        
        //on crée le formulaire de modif a partir du même formulaire que celui de création d'article
        $formulaireModif=$this->createForm(formulaireEventPartType::class, $event);
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            // on check si l'image est modifiée. si oui, on supprime le lien de l'ancienne, on supprime l'image du dossier, et on enregistre la nouvelle
            if($formulaireModif['image']->getData()!=null){
                if($event->getImgEventPart()!=null && $event->getImgEventPart()!=''){
                    unlink($this->getParameter('image_event_part').'/'.$event->getImgEventPart());
                }
                $file=$formulaireModif['image']->getData();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_event_part'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                $event->setImage($formulaireModif['image']->getData());
                $event->setImgEventPart($filename);
                
            }
            $em->flush();
            
            return $this->redirectToRoute('listage_eventPart');
        }
        return $this->render('admin/eventParticiper/editEventPart.html.twig',[
            'formulaireEdit'=>$formulaireModif->createView(),
            'idEvent'=>$event->getId(),
        ]);
    }
    
    
    /**
     * @Route("admin/gestionEventExterieur/eventPart/{id}", name="visualisation_eventPart")
     */
    
    public function viewEvent(EvenementParticiper $event, $id){
        
        return $this->render('admin/eventParticiper/viewEventPart.html.twig', [
            'event'=>$event
        ]);
        
    }
    
    
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
    
}



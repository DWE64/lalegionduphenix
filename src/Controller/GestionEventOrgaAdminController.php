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
use App\Entity\EventOrganise;
use App\Form\formulaireEventOrgaType;
use App\Repository\EventOrganiseRepository;


class GestionEventOrgaAdminController extends AbstractController
{
    /**
     * @Route("/admin/gestionEventOrganise/listeEventOrga", name="listage_event")
     * 
     */
    public function indexAdmin(EventOrganiseRepository $EventOrgaRepository, EntityManagerInterface $em, Request $request)
    {
       
        $hasAccess=$this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $objetEventOrga=new EventOrganise();
        
        $formulaireEventOrga=$this->createForm(formulaireEventOrgaType::class, $objetEventOrga);
        $formulaireEventOrga->handleRequest($request);
        
        if($formulaireEventOrga->isSubmitted() && $formulaireEventOrga->isValid()){
            
            //ici on r�cup�re et on stock l'image dans le dossier public correspondant au service renseign� dans le service.yaml
            if($formulaireEventOrga['image']->getData()!=null){
                $file=$formulaireEventOrga['image']->getData();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_event_orga'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                $objetEventOrga->setImage($formulaireEventOrga['image']->getData());
                $objetEventOrga->setImgEventOrga($filename);
            }
            
            // action :
            $em->persist($objetEventOrga);
            
            //envoi � la bdd
            
            $em->flush();
            
            //message flash
            $type='info';
            $message='nouvel evenement organise cr��';
            $this->addFlash($type, $message);
            
            return $this->redirectToRoute("listage_event");
        }
        return $this->render('admin/eventOrga/newEventOrga.html.twig', [
            'tableauObjetEvent'=>$EventOrgaRepository->findAll(),
            'formulaireEventOrga'=>$formulaireEventOrga->createView(),
        ]);
    }
    /**
     * @Route("/admin/gestionEventOrganise/eventOrga/{id}/delete", name="delete_eventOrga")
     */
    public function deleteEvent(EventOrganise $event, EntityManagerInterface $em){ //ici la fonction r�cup�re l'id de l'article renseign�e dans la route appel�e dans le fichier twig
        
        //on efface le lien d'image s'il y a une image puis on supprime l'image du dossier
        
        if($event->getImgEventOrga()!=null && $event->getImgEventOrga()!=''){
            unlink($this->getParameter('image_event_orga').'/'.$event->getImgEventOrga());
        }
        
        //on supprime l'objet article pass� en param�tre
        $em->remove($event);
        //on dit a la bdd d'effacer l'article en question de la base
        $em->flush();
        // on affiche le message de confirmation
        $type='info';
        $message='evenement supprim�';
        $this->addFlash($type, $message);
        
        //on retourne � la liste
        return $this->redirectToRoute('listage_event');
    }
    
    
    /**
     * @Route("/admin/gestionEventOrganise/eventOrga/{id}/edition", name="edit_eventOrga")
     */
    public function updateEvent(EventOrganise $event, EntityManagerInterface $em, Request $request){ //ici on r�cup�re l'id pass� en param�tre et la r�ponse au formulaire de modification
        
        //on cr�e le formulaire de modif a partir du m�me formulaire que celui de cr�ation d'article
        $formulaireModif=$this->createForm(formulaireEventOrgaType::class, $event);
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            // on check si l'image est modifi�e. si oui, on supprime le lien de l'ancienne, on supprime l'image du dossier, et on enregistre la nouvelle
            if($formulaireModif['image']->getData()!=null){
                if($event->getImgEventOrga()!=null && $event->getImgEventOrga()!=''){
                    unlink($this->getParameter('image_event_orga').'/'.$event->getImgEventOrga());
                }
                $file=$formulaireModif['image']->getData();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_event_orga'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                $event->setImage($formulaireModif['image']->getData());
                $event->setImgEventOrga($filename);
                
            }
            $em->flush();
            
            return $this->redirectToRoute('listage_event');
        }
        return $this->render('admin/eventOrga/editEventOrga.html.twig',[
            'formulaireEdit'=>$formulaireModif->createView(),
            'idEvent'=>$event->getId(),
        ]);
    }
    
    
    /**
     * @Route("admin/gestionEventOrganise/eventOrga/{id}", name="visualisation_eventOrga")
     */
    
    public function viewEvent(EventOrganise $event, $id){
        
        return $this->render('admin/eventOrga/viewEventOrga.html.twig', [
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



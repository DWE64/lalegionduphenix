<?php

namespace App\Controller;

use App\Entity\EvenementParticiper;
use App\Entity\Jeu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActiviteController extends AbstractController
{
    /**
     * @Route("/activite", name="activite")
     */
    public function index()
    {
       
            
            
            //affichage des différentes activités
            $listeJeu=ActiviteController::listeJeu();
            
            $listeEventPart=ActiviteController::listeEventPart();
            
                        
            
            return  $this->render('site/activite.html.twig',[
                "listeJeu"=>$listeJeu,
                "listeEventPart"=>$listeEventPart,
            ]);
     }
        
        
        
        
        /**
         *
         * @Route("/activite/listeJeu", name="activite_listage_Jeu")
         */
        
   public function listeJeu(){
         $jeu=$this->getDoctrine()->getRepository(Jeu::class)->findAll();
            
         return $jeu;
    }
    
    /**
     *
     * @Route("/activite/listeEventPart", name="activite_listage_event_part")
     */
    
    public function listeEventPart(){
        $eventPart=$this->getDoctrine()->getRepository(EvenementParticiper::class)->findAll();
        
        return $eventPart;
    }
}

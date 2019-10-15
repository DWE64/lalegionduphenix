<?php

namespace App\Controller;

use App\Entity\EvenementParticiper;
use App\Entity\Jeu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JeuRepository;
use App\Repository\EvenementParticiperRepository;
use App\Repository\EventOrganiseRepository;

class ActiviteController extends AbstractController
{
    /**
     * @Route("/activite", name="activite")
     */
    public function index(JeuRepository $jeuRepository, EvenementParticiperRepository $eventParticiperRepository, EventOrganiseRepository $eventOrganiseRepository)
    {
          return  $this->render('site/activite.html.twig',[
                "listeJeu"=>$jeuRepository->findAll(),
                "listeEventOrga"=>$eventOrganiseRepository->findAll(),
                "listeEventPart"=>$eventParticiperRepository->findAll(),
            ]);
     }
        
     
}

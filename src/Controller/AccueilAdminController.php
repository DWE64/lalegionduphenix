<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AccueilAdminController extends AbstractController
{
    /**
     * @Route("/admin/accueilAdmin", name="accueilAdmin")
     */
    public function indexAdmin()
    {
        // necessaire pour faire le tri sur l'autorisation de connexion. chercher aussi pour SUPER_ADMIN
        $hasAccess=$this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $messageBienvenu="page de connexion admin reussie!";
        
        return  $this->render('admin/accueilAdmin.html.twig',[
            'message'=>$messageBienvenu
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index()
    {
        $message="Page Accueil";
        return $this->render('accueil/index.html.twig', [
            'titrePage' => $message,
        ]);
    }
}

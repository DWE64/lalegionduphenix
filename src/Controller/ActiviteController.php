<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActiviteController extends AbstractController
{
    /**
     * @Route("/activite", name="activite")
     */
    public function index()
    {
        $message="Page Activite";
        return $this->render('site/activite.html.twig', [
            'titrePage' => $message,
        ]);
    }
}

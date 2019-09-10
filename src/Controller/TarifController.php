<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TarifController extends AbstractController
{
    /**
     * @Route("/tarif", name="tarif")
     */
    public function index()
    {
        $message="Page Tarif";
        return $this->render('site/tarif.html.twig', [
            'titrePage' => $message,
        ]);
    }
}

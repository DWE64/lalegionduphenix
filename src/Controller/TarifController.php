<?php

namespace App\Controller;

use App\Repository\FichierPageTarifRepository;
use App\Repository\InformationPageTarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TarifController extends AbstractController
{
    /**
     * @Route("/tarif", name="tarif")
     */
    public function index(FichierPageTarifRepository $fichierRepository, InformationPageTarifRepository $information)
    {
        $infoRecente=$information->find(1);
       
        return $this->render('site/tarif.html.twig', [
            'info' => $infoRecente,
            'objetFichier' => $fichierRepository->findAll(),
        ]);
    }
}

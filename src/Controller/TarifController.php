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
        $infoRecente=$information->getLastInfo();
       
        return $this->render('site/tarif.html.twig', [
            'infoTableau' => $infoRecente,
            'objetFichier' => $fichierRepository->findAll(),
        ]);
    }
}

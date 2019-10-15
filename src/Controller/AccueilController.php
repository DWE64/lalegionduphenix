<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(ArticleRepository $article)
    {
        
        return $this->render('site/accueil.html.twig', [
            'tableauObjetArticle'=>$article->findAll(),
        ]);
    }
}

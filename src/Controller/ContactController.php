<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(LieuRepository $lieu, EntityManagerInterface $em, Request $request)
    {
        
        return $this->render('site/contact.html.twig', [
            'tableauLieu' => $lieu->findAll(),
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Contact;
use App\Form\formulaireContactType;
use App\Service\ContactNotification;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(LieuRepository $lieu, EntityManagerInterface $em, Request $request, ContactNotification $notification)
    {
        $contact=new Contact();
        $formContact=$this->createForm(formulaireContactType::class, $contact);
        
        $formContact->handleRequest($request);
        if($formContact->isSubmitted() && $formContact->isValid()){
            
            $notification->contactNotify($contact);
            
            
            $type='success';
            $messageFlash='Votre email a bien été envoyé';
            $this->addFlash($type, $messageFlash);
            
            return $this->redirectToRoute("contact");
            
        }
        
        return $this->render('site/contact.html.twig', [
            'tableauLieu' => $lieu->findAll(),
            'formContact' =>$formContact->createView(),
        ]);
    }
}

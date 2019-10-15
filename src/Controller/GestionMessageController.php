<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message;
use App\Form\formulaireMessageType;

class GestionMessageController extends AbstractController
{
    /**
     * @Route("admin/boiteMail", name="listeMessage")
     */
    public function index(MessageRepository $messageRepo)
    {
        $hasAccess=$this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        
        return $this->render('admin/boiteMail/listeMessage.html.twig', [
            'listeMessage' => $messageRepo->findAll(),
        ]);
    }
    
    /**
     * @Route("admin/boiteMail/{id}/reponse", name="reponse_message")
     */
    public function envoiMail(Message $message, \Swift_Mailer $mail, EntityManagerInterface $em, Request $request){
        $objetMessage=new Message();
        
        $formulaireMessage=$this->createForm(formulaireMessageType::class, $objetMessage);
        $formulaireMessage->handleRequest($request);
        
        if($formulaireMessage->isSubmitted() && $formulaireMessage->isValid()){
            
            //ici on récupère et on stock l'image dans le dossier public correspondant au service renseigné dans le service.yaml
            $mail=(new \Swift_Message())
            ->setSubject($formulaireMessage['sujet'])
            ->setFrom('lalegionduphenix@hotmail.fr')
            ->setTo($formulaireMessage['emailExpediteur'])
            ->setBody($formulaireMessage['message']);
            // action :
            $em->persist($objetMessage);
            
            //envoi à la bdd
            
            $em->flush();
            
            //message flash
            $type='info';
            $message='nouveau jeu créé';
            $this->addFlash($type, $message);
            
            return $this->redirectToRoute("listeMessage");
        }
        return $this->render('admin/boiteMail/reponse.html.twig', [
            'formMessage' => $formulaireMessage->createView(),
        ]);
    }
    
    /**
     * @Route("admin/boiteMail/{id}/delete", name="delete_message")
     */
    public function deleteMessage(Message $message, EntityManagerInterface $em){
        $em->remove($message);
        //on dit a la bdd d'effacer l'article en question de la base
        $em->flush();
        // on affiche le message de confirmation
        $type='info';
        $messageInfo='message supprimé';
        $this->addFlash($type, $messageInfo);
        
        //on retourne à la liste
        return $this->redirectToRoute('listeMessage');
    }
    /**
     * @Route("admin/boiteMail/{id}", name="visualisation_message")
     */
    
    public function viewMessage(Message $message, $id){
        
        return $this->render('admin/boiteMail/viewMessage.html.twig', [
            'message'=>$message
        ]);
        
    }
}

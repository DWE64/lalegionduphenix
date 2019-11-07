<?php
namespace App\Service;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification
{
    /**
     * 
     * @var \Swift_Mailer
     */
    private $mailer;
    
    /**
     * 
     * @var Environment
     */
    private $vueTwig;
    
    
    public function __construct(\Swift_Mailer $mailer, Environment $vueTwig){
        $this->mailer=$mailer;
        $this->vueTwig=$vueTwig;
    }
    
    public function contactNotify(Contact $contact){
        var_dump($contact);
        
        $message = (new \Swift_Message('Nouveau message : '.$contact->getSujet()))
        ->setFrom($contact->getMail())
        ->setTo('lalegionduphenix@hotmail.fr')
        ->setReplyTo($contact->getMail())
        ->setBody($this->vueTwig->render('emails/contact.html.twig',[
            'contact'=>$contact,
        ]), 'text/html');
        
        $this->mailer->send($message);
        
    }
}


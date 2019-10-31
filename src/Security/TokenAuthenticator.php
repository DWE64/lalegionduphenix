<?php
namespace App\Security;
use App\Entity\User;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class TokenAuthenticator extends AbstractGuardAuthenticator{
    
    private $em;
    
    public function __contruct(EntityManagerInterface $em){
        $this->em=$em;
    }
    
    //demande d'initialisation de l'authentification => true (ou valeur) pour oui, false pour non
    public function supports(Request $request){
        return $request->headers->has('X-AUTH-TOKEN');
    }
    
    //lit l'information a controler et renvoie l'information à la method getUser()
    public function getCredentials(Request $request){
        if($token=='ILuvAPIs'){
            throw new CustomUserMessageAuthenticationException('ILuvAPIs est une clé invalide');
        }
        
        return ['token' => $request->headers->get('X-AUTH-TOKEN'),];
    }
    
    
    //recherche dans la bdd de l'user avec l'identificateur envoyé par la méthode getCredentials
    //si valeur retourné, appel de la methode checkCredential()
    public function getUser($credentials, UserProviderInterface $userProvider){
        $apiToken=$credentials['token'];
        
        if(null == $apiToken){
            return;
        }
        
        return $this->em->getRepository(User::class)->findOneBy(['apiToken'=>$apiToken]);
    }
    
    
    //checker ici si les informations de l'objet user sont conforme au valeur du formulaire de connexion
    //si identification bonne => return true, sinon erreur levée ou retour d'autre chose => echec identification
    public function checkCredentials($credentials, UserInterface $user){
        return true;
    }
    
    //ici on autorise l'accès à la route en renvoyant un objet Reponse. on peut aussi pousuivre l'envoi null au client
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey){
        return null;
    }
    
    
    //ici on envoi un echec de connexion au client avec l'objet reponse et l'exception levée
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception){
        $data=[
            'message'=>strtr($exception->getMessageKey(), $exception->getMessageData())
        ];
        
        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }
    
    
    //méthod utilisée si aucun information n'est envoyé a la base, et aide l'utilisateur a s'identifier
    public function start(Request $request, AuthenticationException $authException=null){
        
       $data=['message'=>'Authentication Required'];
       
       return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
    
    
    //permet de prendre en compte la fonctionnalité "se souvenir de moi"
    public function supportsRememberMe(){
        return false;
    }
    
}


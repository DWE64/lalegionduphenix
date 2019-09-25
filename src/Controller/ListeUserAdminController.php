<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;


class ListeUserAdminController extends AbstractController
{
    /**
     * @Route("/admin/listeMembre", name="listeUser")
     */
    public function indexAdmin():Response
    {
        // necessaire pour faire le tri sur l'autorisation de connexion. chercher aussi pour SUPER_ADMIN
       $hasAccess=$this->isGranted('ROLE_SUPER_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
       //$listeUser=ListeUserAdminController::listeUser();
       $user=ListeUserAdminController::listeUser();
       
        
       $messageBienvenu="Liste des utilisateur du site!";
        
      
        
        
        return  $this->render('admin/listeUtilisateurAdmin.html.twig',[
            'message'=>$messageBienvenu,
            "listeUser"=>$user
        ]);
    }
    
    
    public function listeUser(){
          $user=$this->getDoctrine()->getRepository(User::class)->findAll();
          
          if(!$user){
              throw $this->createNotFoundException('Aucun utilisateur trouvé dans la base');
          }
          
          return $user;
    }
    
    /**
     * @Route("/admin/supprimerUser", name="delete_user")
     */
    public function deleteUser($id){
        $entityManager=$this->getDoctrine()->getManager();
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();
        
        return $this->redirectToRoute('listeUser');
        
    }
    
    /**
     * @Route("/admin/modifStatut", name="update_statut_user")
     */
    public function updateStatutUser(){
        
    }
       
    
}



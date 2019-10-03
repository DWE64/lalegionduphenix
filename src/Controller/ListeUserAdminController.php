<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use App\Form\ModificationRoleUser;
use App\Form\SuppressionUser;


class ListeUserAdminController extends AbstractController
{
    /**
     * @Route("/admin/listeMembre", name="listeUser")
     */
    public function indexAdmin(Request $request):Response
    {
        
       $hasAccess=$this->isGranted('ROLE_SUPER_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
       //affichage des utilisateurs
       $listeUser=ListeUserAdminController::listeUser();
       
       //formulaire de modification de statut
       
       $user=new User();
       $formulaireModif=$this->createForm(ModificationRoleUser::class, $user,[
           'action'=>$this->generateUrl('update_statut_user'),
           'method'=>'POST',
       ]);
       

       //formulaire de suppression d'utilisateur
       
       $formulaireSup=$this->createForm(SuppressionUser::class, $user,[
           'action'=>$this->generateUrl('delete_user'),
           'method'=>'POST',
       ]);
       
       
        return  $this->render('admin/listeUtilisateurAdmin.html.twig',[
            "listeUser"=>$listeUser,
            "formModifStatut"=>$formulaireModif->createView(),
            "formSup"=>$formulaireSup->createView(),
        ]);
    }
    
    
    
    
    /**
     * 
     * @Route("/admin/listeUser", name="listage_user")
     */
    
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
    public function deleteUser(Request $request){
        $supUser=new User();
        $formulaireSup=$this->createForm(SuppressionUser::class, $supUser);
        $formulaireSup->handleRequest($request);
        
        
        if($formulaireSup->isSubmitted() && $formulaireSup->isValid()){
        $entityManager=$this->getDoctrine()->getManager();
        $user=$entityManager->getRepository(User::class)->find($supUser->getId());
        if(!$user){
            throw $this->createNotFoundException('aucun utilisateur a supprimer avec cet identifiant: '.$supUser->getId());
        }
        $entityManager->remove($user);
        $entityManager->flush();
        
        return $this->redirectToRoute('listeUser');
        }
        return $this->redirectToRoute('listeUser');
        
    }
    
    /**
     * @Route("/admin/modifStatut", name="update_statut_user")
     */
    public function updateStatutUser(Request $request){
        $statut=new User();
        $formulaireModif=$this->createForm(ModificationRoleUser::class, $statut);
        
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
        $entityManager=$this->getDoctrine()->getManager();
        $roleUser=$entityManager->getRepository(User::class)->find($statut->getId());
        
        if(!$roleUser){
            throw $this->createNotFoundException('aucun utilisateur a modifier avec cet identifiant: '.$statut->getId());
        }
        
        if($statut->getIdStatutUser()!=null){
            switch($statut->getIdStatutUser()){
                case 1:
                    $role=["ROLE_SUPER_ADMIN"];
                    $nomStatut="Super Administrateur";
                    $idStatut=1;
                    break;
                case 2:
                    $role=["ROLE_ADMIN"];
                    $nomStatut="Administrateur";
                    $idStatut=2;
                    break;
                case 3:
                    $role=["ROLE_USER"];
                    $nomStatut="Membre";
                    $idStatut=3;
                    break;
                case 4:
                    $role=[""];
                    $nomStatut="Nouvel Utilisateur";
                    $idStatut=4;
                    break;
                default:
                    $role=[""];
                    $nomStatut="Nouvel Utilisateur";
                    $idStatut=4;
                    
            }
        }
        $roleUser->setRoles($role);
        $roleUser->setStatut($nomStatut);
        $roleUser->setIdStatutUser($idStatut);
        
        $entityManager->flush();
        
        return $this->redirectToRoute('listeUser');
        }
        return $this->redirectToRoute('listeUser');
    }
    
    
}



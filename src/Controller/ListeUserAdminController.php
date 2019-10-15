<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use App\Repository\UserRepository;
use App\Form\formulaireUserType;


class ListeUserAdminController extends AbstractController
{
    /**
     * @Route("/admin/listeMembre", name="listeUser")
     */
    public function indexAdmin(UserRepository $user)
    {
        
       $hasAccess=$this->isGranted('ROLE_SUPER_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
               
       
        return  $this->render('admin/user/listeUser.html.twig',[
            "tableauObjetUser"=>$user->findAll(),
        ]);
    }
    
    
    /**
     * @Route("/admin/listeMembre/{id}/delete", name="delete_user")
     */
    public function deleteUser(User $user, EntityManagerInterface $em){
        
        $em->remove($user);
        $em->flush();
        
        $type='info';
        $message='utilisateur supprimé de la base';
        $this->addFlash($type, $message);
        
        return $this->redirectToRoute('listeUser');
        
    }
    
    /**
     * @Route("/admin/listeMembre/{id}/edit", name="edit_user")
     */
    public function updateUser(User $user, EntityManagerInterface $em, Request $request){
        
        $formulaireModif=$this->createForm(formulaireUserType::class, $user);
        
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
       
        
           
                switch($formulaireModif['idStatutUser']->getData()){
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
            $user->setRoles($role);
            $user->setStatut($nomStatut);
            $user->setIdStatutUser($idStatut);
        
            $em->flush();
        
            return $this->redirectToRoute('listeUser');
        }
        return $this->render('admin/user/editUser.html.twig',[
            'formulaireEdit'=>$formulaireModif->createView(),
            'user'=>$user,
        ]);
    }
    
    /**
     * @Route("/admin/listeMembre/{id}", name="visualisation_user")
     */
    public function viewUser(User $user, $id){
        return $this->render('admin/user/viewUser.html.twig',[
            'user'=>$user,
        ]);
    }
    
    
}



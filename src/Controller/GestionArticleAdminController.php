<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Form\ModificationArticle;
use App\Form\SuppressionArticle;
use App\Form\formulaireArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\Length;
use App\Repository\ArticleRepository;



class GestionArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/listeArticle", name="listeArticle")
     */
    public function indexAdmin(ArticleRepository $articleRepository, EntityManagerInterface $em, Request $request)
    {
      // fonction permettant l'affichage des articles ET la création d'un nouvel article
       $hasAccess=$this->isGranted('ROLE_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       
       $objetArticle=new Article();
       
       $formulaireArticle=$this->createForm(formulaireArticleType::class, $objetArticle);
       $formulaireArticle->handleRequest($request);
       
       if($formulaireArticle->isSubmitted() && $formulaireArticle->isValid()){
           
           //ici on récupère et on stock l'image dans le dossier public correspondant au service renseigné dans le service.yaml
           if($formulaireArticle['image']->getData()!=null){
               $file=$formulaireArticle['image']->getData();
               $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
               
               try{
                   $file->move($this->getParameter('image_article'), $filename);
                   
               }catch(FileException $e){
                   return $e;
               }
               $objetArticle->setImage($formulaireArticle['image']->getData());
               $objetArticle->setImgArticle($filename);
           }
           
           // action : 
           $em->persist($objetArticle);
           
           //envoi à la bdd
           
           $em->flush();
           
           //message flash
           $type='info';
           $message='nouvel article créé';
           $this->addFlash($type, $message);
           
           return $this->redirectToRoute("listeArticle");
       }
      return $this->render('admin/article/newArticle.html.twig', [
          'tableauObjetArticle'=>$articleRepository->findAll(),
          'formulaireArticle'=>$formulaireArticle->createView(),
        ]);
     }
       
      
     /**
      * @Route("/admin/Article/{id}/delete", name="delete_article")
      */
     public function deleteArticle(Article $article, EntityManagerInterface $em){ //ici la fonction récupère l'id de l'article renseignée dans la route appelée dans le fichier twig
         
         //on efface le lien d'image s'il y a une image puis on supprime l'image du dossier
         
         if($article->getImgArticle()!=null && $article->getImgArticle()!=''){
             unlink($this->getParameter('image_article').'/'.$article->getImgArticle());
         }      
                  
         //on supprime l'objet article passé en paramètre
         $em->remove($article);
         //on dit a la bdd d'effacer l'article en question de la base
         $em->flush();
         // on affiche le message de confirmation
         $type='info';
         $message='article supprimé';
         $this->addFlash($type, $message);
         
         //on retourne à la liste
         return $this->redirectToRoute('listeArticle');
     }
    
    
    /**
     * @Route("/admin/Article/{id}/edition", name="edit_article")
     */
    public function updateArticle(Article $article, EntityManagerInterface $em, Request $request){ //ici on récupère l'id passé en paramètre et la réponse au formulaire de modification
        
        //on crée le formulaire de modif a partir du même formulaire que celui de création d'article
        $formulaireModif=$this->createForm(formulaireArticleType::class, $article);
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            // on check si l'image est modifiée. si oui, on supprime le lien de l'ancienne, on supprime l'image du dossier, et on enregistre la nouvelle
            if($formulaireModif['image']->getData()!=null){
                if($article->getImgArticle()!=null && $article->getImgArticle()!=''){
                    unlink($this->getParameter('image_article').'/'.$article->getImgArticle());
                }
                $file=$formulaireModif['image']->getData();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_article'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                $article->setImage($formulaireModif['image']->getData());
                $article->setImgArticle($filename);
                
            }
            $em->flush();
        
            return $this->redirectToRoute('listeArticle');
        }
        return $this->render('admin/article/editArticle.html.twig',[
            'formulaireEdit'=>$formulaireModif->createView(),
            'idArticle'=>$article->getId(),
        ]);
    }
    
    
    /**
     * @Route("admin/Article/{id}", name="visualisation_article")
     */
   
    public function viewArticle(Article $article, $id){
        
        return $this->render('admin/article/viewArticle.html.twig', [
           'article'=>$article 
        ]);
        
    }
    
    
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
    
}



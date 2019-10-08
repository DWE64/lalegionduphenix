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
      // fonction permettant l'affichage des articles ET la cr�ation d'un nouvel article
       $hasAccess=$this->isGranted('ROLE_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       
       $objetArticle=new Article();
       
       $formulaireArticle=$this->createForm(formulaireArticleType::class, $objetArticle);
       $formulaireArticle->handleRequest($request);
       
       if($formulaireArticle->isSubmitted() && $formulaireArticle->isValid()){
           
           //ici on r�cup�re et on stock l'image dans le dossier public correspondant au service renseign� dans le service.yaml
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
           
           //envoi � la bdd
           
           $em->flush();
           
           //message flash
           $type='info';
           $message='nouvel article cr��';
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
     public function deleteArticle(Article $article, EntityManagerInterface $em){ //ici la fonction r�cup�re l'id de l'article renseign�e dans la route appel�e dans le fichier twig
         
         //on efface le lien d'image s'il y a une image puis on supprime l'image du dossier
         
         if($article->getImgArticle()!=null && $article->getImgArticle()!=''){
             unlink($this->getParameter('image_article').'/'.$article->getImgArticle());
         }      
                  
         //on supprime l'objet article pass� en param�tre
         $em->remove($article);
         //on dit a la bdd d'effacer l'article en question de la base
         $em->flush();
         // on affiche le message de confirmation
         $type='info';
         $message='article supprim�';
         $this->addFlash($type, $message);
         
         //on retourne � la liste
         return $this->redirectToRoute('listeArticle');
     }
    
    
    /**
     * @Route("/admin/Article/{id}/edition", name="edit_article")
     */
    public function updateArticle(Article $article, EntityManagerInterface $em, Request $request){ //ici on r�cup�re l'id pass� en param�tre et la r�ponse au formulaire de modification
        
        //on cr�e le formulaire de modif a partir du m�me formulaire que celui de cr�ation d'article
        $formulaireModif=$this->createForm(formulaireArticleType::class, $article);
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            // on check si l'image est modifi�e. si oui, on supprime le lien de l'ancienne, on supprime l'image du dossier, et on enregistre la nouvelle
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



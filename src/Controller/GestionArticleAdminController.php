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



class GestionArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/gestionArticle", name="gestionArticle")
     */
    public function indexAdmin(Request $request):Response
    {
      
       $hasAccess=$this->isGranted('ROLE_ADMIN');
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
       //affichage des différentes activités
       $listeArticle=GestionArticleAdminController::listeArticle();
       
       //formulaire d'ajout d'activité
       
       $article=new Article();
       $formNewArticle=$this->createForm(formulaireArticleType::class, $article,[
           'action'=>$this->generateUrl('ajout_article'),
           'method'=>'POST',
       ]);
       
       //formulaire de modification d'article
       
       
      $formModifArticle=$this->createForm(ModificationArticle::class, $article,[
           'action'=>$this->generateUrl('update_article'),
           'method'=>'POST',
       ]);
       
              
       //formulaire de suppression d'article
       
       
       $formSupArticle=$this->createForm(SuppressionArticle::class, $article,[
           'action'=>$this->generateUrl('delete_article'),
           'method'=>'POST',
       ]);
       
       return  $this->render('admin/gestionArticleAdmin.html.twig',[
            "listeArticle"=>$listeArticle,
            "formNewArticle"=>$formNewArticle->createView(),
            "formModifArticle"=>$formModifArticle->createView(),
            "formSup"=>$formSupArticle->createView(),
        ]);
    }
    
    
    
    
    /**
     * 
     * @Route("/admin/listeArticle", name="listage_article")
     */
    
    public function listeArticle(){
          $articleListe=$this->getDoctrine()->getRepository(Article::class)->findAll();
                    
          return $articleListe;
    }
    
    /**
     * @Route("/admin/ajoutArticle", name="ajout_article")
     */
    public function newArticle(Request $request){
        $article=new Article();
        $formulaireModif=$this->createForm(formulaireArticleType::class, $article);
        
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            if($article->getImage()!=null){
                $file=$article->getImage();
                $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
                
                try{
                    $file->move($this->getParameter('image_article'), $filename);
                    
                }catch(FileException $e){
                    return $e;
                }
                
                $article->setImgArticle($filename);
            }
            
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            
            
            return $this->redirectToRoute('gestionArticle');
            
        }
        return $this->redirectToRoute('gestionArticle');
    }
    
    /**
     * @Route("/admin/modifArticle", name="update_article")
     */
    public function updateArticle(Request $request){
        $article=new Article();
        $formulaireModif=$this->createForm(ModificationArticle::class, $article);
        
        $formulaireModif->handleRequest($request);
        
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
        $entityManager=$this->getDoctrine()->getManager();
        $articleTrouve=$entityManager->getRepository(Article::class)->find($article->getId());
        
        if(!$articleTrouve){
            throw $this->createNotFoundException('aucun jeu a modifier avec cet identifiant: '.$article->getId());
        }
        
        if($article->getTitre()!='' && $article->getTitre()!=null){
            $articleTrouve->setTitre($article->getTitre());
        }
        if($article->getSousTitre()!='' && $article->getSousTitre()!=null){
            $articleTrouve->setSousTitre($article->getSousTitre());
        }
        if($article->getDescription()!='' && $article->getDescription()!=null){
            $articleTrouve->setDescription($article->getDescription());
        }
        if($article->getImage()!=null){
            if($articleTrouve->getImgArticle()!=null && $articleTrouve->getImgArticle()!=''){
                unlink($this->getParameter('image_article').'/'.$articleTrouve->getImgArticle());
            }
            $file=$article->getImage();
            $filename=$this->generateUniqueFileName().'.'.$file->guessExtension();
            
            try{
                $file->move($this->getParameter('image_article'), $filename);
                
            }catch(FileException $e){
                return $e;
            }
            
            $articleTrouve->setImgArticle($filename);
            
        }
                
        $entityManager->flush();
        
        return $this->redirectToRoute('gestionArticle');
        }
        return $this->redirectToRoute('gestionArticle');
    }
    
    /**
     * @Route("/admin/supprimerArticle", name="delete_article")
     */
    public function deleteArticle(Request $request){
        $article=new Article();
        $formulaireSup=$this->createForm(SuppressionArticle::class, $article);
        $formulaireSup->handleRequest($request);
        
        
        if($formulaireSup->isSubmitted() && $formulaireSup->isValid()){
            $entityManager=$this->getDoctrine()->getManager();
            $articleSup=$entityManager->getRepository(Article::class)->find($article->getId());
            if(!$articleSup){
                throw $this->createNotFoundException('aucun article a supprimer avec cet identifiant: '.$article->getId());
            }
            unlink($this->getParameter('image_article').'/'.$articleSup->getImgArticle());
            $entityManager->remove($articleSup);
            $entityManager->flush();
            
            return $this->redirectToRoute('gestionArticle');
        }
        return $this->redirectToRoute('gestionArticle');
        
    }
    
    
    /**
     * @return string
     */
    private function generateUniqueFileName(){
        return md5(uniqid());
    }
    
}



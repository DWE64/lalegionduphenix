<?php
namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Jeu;

class ModificationJeu extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('id', IntegerType::class,[
            'attr'=>[
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1',
                'placeholder'=>'Id_User',
            ]
        ])
        ->add('titre', TextType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'titre',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('description', TextareaType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Description du jeu',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('image', FileType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Photo du jeu',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
            
        ])
        ->add('modifier', SubmitType::class,[
            'attr'=>[
                'placeholder'=>'Modifier',
                'class'=>'btn btn-primary col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'
            ]
        ]);
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>Jeu::class,]);
    }



}


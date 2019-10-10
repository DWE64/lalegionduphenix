<?php
namespace App\Form;

use App\Entity\EventOrganise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class formulaireEventOrgaType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('titre', TextType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'titre',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('theme', TextType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'theme',
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
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>EventOrganise::class,]);
    }



}


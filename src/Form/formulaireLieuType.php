<?php
namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Entity\Lieu;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class formulaireLieuType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('nom', TextType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Nom du lieu',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('adresse', TextType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'adresse',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('codePostal', IntegerType::class, [
            'required'=>true,
            'attr'=>[
                "placeholder"=>"codePostal",
                'class'=>'form-control col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('ville', TextType::class, [
            'required'=>true,
            'attr'=>[
                "placeholder"=>"ville",
                'class'=>'form-control col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('heureDebut', IntegerType::class, [
            'required'=>true,
            'attr'=>[
                "placeholder"=>"Heure de debut",
                'class'=>'form-control col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('heureFin', IntegerType::class, [
            'required'=>true,
            'attr'=>[
                "placeholder"=>"Heure de fin",
                'class'=>'form-control col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('urlSite', TextType::class, [
            'required'=>true,
            'attr'=>[
                "placeholder"=>"Site web",
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('telephone', TextType::class, [
            'required'=>true,
            'attr'=>[
                "placeholder"=>"Telephone",
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('image', FileType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Photo',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
            
        ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>Lieu::class,]);
    }



}


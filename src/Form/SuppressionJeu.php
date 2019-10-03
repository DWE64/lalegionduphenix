<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Jeu;

class SuppressionJeu extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('id', IntegerType::class,[
            'attr'=>[
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-1',
                'placeholder'=>'Id_User',
            ]
        ])
        ->add('supprimer', SubmitType::class,[
            'attr'=>[
                'placeholder'=>'Supprimer',
                'class'=>'btn btn-primary col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-1'
            ]
        ]);
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>Jeu::class,]);
    }



}


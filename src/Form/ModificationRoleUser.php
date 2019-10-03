<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModificationRoleUser extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('id', IntegerType::class,[
            'attr'=>[
                'class'=>'form-control col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 mr-2 mb-2',
                'placeholder'=>'Id_User',
            ]
        ])
        ->add('idStatutUser', ChoiceType::class,[
            'choices'=>['Super Adminitrateur'=>1, "Administrateur"=>2, "Membre"=>3, "Nouvel Utilisateur"=>4],
            'attr'=>['class'=>'form-control custom-select col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12'],
        ])
        ->add('modifier', SubmitType::class,[
            'attr'=>[
                'placeholder'=>'Modifier',
                'class'=>'btn btn-primary col-xl-11 col-lg-11 col-md-11 col-sm-12 col-12'
            ]
        ]);
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>User::class,]);
    }



}


<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class formulaireUserType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('idStatutUser', ChoiceType::class,[
            'choices'=>['Super Adminitrateur'=>1, "Administrateur"=>2, "Membre"=>3, "Nouvel Utilisateur"=>4],
            'attr'=>['class'=>'form-control custom-select col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12'],
        ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>User::class,]);
    }



}


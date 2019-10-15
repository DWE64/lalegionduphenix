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
use App\Entity\Message;

class formulaireMessageType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('emailExpediteur', EmailType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'votre mail',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('sujet', TextType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'sujet',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('message', TextareaType::class, [
            'required'=>true,
            'attr'=>[
                "placeholder"=>"votre message ...",
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>Message::class,]);
    }



}


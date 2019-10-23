<?php
namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\InformationPageTarif;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class formulaireInfoTarifType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('tarif', IntegerType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Cotisation',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('seanceEssai', IntegerType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Nombre de seance offerte',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>InformationPageTarif::class,]);
    }



}


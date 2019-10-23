<?php
namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\FichierPageTarif;
use Symfony\Component\Validator\Constraints\File;

class formulaireFichierUploadType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('nomAffichage', TextType::class, [
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Nom du fichier',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
        ])
        ->add('nomFichier', FileType::class, [
            'required'=>true,
            'label'=> 'Votre fichier',
            'constraints'=>[
                new File([
                    'maxSize'=>'1024k',
                    'mimeTypes'=>[
                        'application/pdf','application/x-pdf',
                    ],
                    'mimeTypesMessage'=>'Veuillez charger un fichier pdf valide',
                ])
            ],
            'attr'=>[
                'placeholder'=>'fichier telechargeable',
                'class'=>'form-control col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mr-2 mb-1'
            ]
            
        ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['data_class'=>FichierPageTarif::class,]);
    }



}


<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Gouvernorat;
use App\Entity\Structure;
use App\Entity\TypeContrat;
use App\Entity\TypeOperation;
use App\Repository\ContratRepository;
use App\Repository\StructureRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContratActeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('codeCentre',EntityType::class,[
                'class'=>Structure::class,
                'query_builder'=>function(StructureRepository $er)
                {
                    return $er->createQueryBuilder('c')
                            ->where('c.lib LIKE :centre')
                            ->setParameter('centre','centre%')
                             //->orderBy('u.lib','ASC')
                             ;
                },
                'choice_label'=>'lib',
                'placeholder'=>'Selectionner un centre ',
                'label'=>'Centre',
            ])

            ->add('codeRecette',NumberType::class,[
                'label'=>'Recette', 
            ])
           
            ->add('typeContrat',EntityType::class,[
                'class'=>TypeContrat::class,
                'choice_label'=>'libelle',
                'placeholder'=>'Selectionner un type ',
                'label'=>'Type de contrat', 
            ])
            ->add('typeOperation',EntityType::class,[
                'class'=>TypeOperation::class,
                'choice_label'=>'libelle',
                'placeholder'=>'Selectionner un type ',
                'label'=>'Type d\'opération',
            ])
            ->add('reference',TextType::class,[
                'label'=>'Réference',   
            ])
            ->add('dateContrat',DateType::class,[
                'label'=>'Date de Contrat',
                'widget' => 'single_text',
            ])
            ->add('redacteur',TextType::class,[
                'label'=>'Rédacateur',  
            ])
            ->add('montant',NumberType::class,[
                'label'=>'Montant',    
            ])
            ->add('adresseEmp',EntityType::class,[
                'class'=>Gouvernorat::class,
                'choice_label'=>'libelle',
                'placeholder'=>'Selectionner une adresse ',
                'label'=>'Adresse',   
            ])
            // ->add('doc',FileType::class,[
            //     'label'=>'Fichier',
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '1024k',
            //             'mimeTypes' => [
            //                 'application/pdf',
            //                 'application/x-pdf',
            //             ],
            //             'mimeTypesMessage' => 'Veuillez télécharger un document PDF ',
            //         ])
            //     ],
                        
            // ])
            ->add('identifiantV',TextType::class,[
                'label'=>'CIN',
                'mapped'=>false,
            ])
            // ->add('typeV',TextType::class,[
            //     'label'=>'Type',
            //     'mapped'=>false,
            // ])
            ->add('nomV',TextType::class,[
                'label'=>'Nom de vendeur',
                'mapped'=>false,
            ])
            ->add('prenomV',TextType::class,[
                'label'=>'Prénom de vendeur',
                'mapped'=>false,
            ])
            ->add('identifiantA',TextType::class,[
                'label'=>'CIN ',
                'mapped'=>false,
            ])
            // ->add('typeA',TextType::class,[
            //     'label'=>'Type',
            //     'mapped'=>false,
            // ])
            ->add('nomA',TextType::class,[
                'label'=>'Nom de acheteur',
                'mapped'=>false,
            ])
            ->add('prenomA',TextType::class,[
                'label'=>'Prénom de acheteur',
                'mapped'=>false,
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           // 'data_class' => Contrat::class,
        ]);
    }
}

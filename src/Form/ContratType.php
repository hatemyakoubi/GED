<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Structure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codeCentre',NumberType::class,[
                'label'=>'Code de centre',
            ])
            ->add('codeRecette',NumberType::class,[
                'label'=>'Code de recette',     
            ])
           
            ->add('typeContrat',TextType::class,[
                'label'=>'Type de contrat',          
            ])
            ->add('typeOperation',TextType::class,[
                'label'=>'Type d\'opération',               
            ])
            ->add('reference',TextType::class,[
                'label'=>'Réference',        
            ])
            ->add('dateContrat',DateType::class,[
                'label'=>'Date de Contrat',        
            ])
           // ->add('dateModification',DateType::class)
            //->add('dateSauvegarde',DateType::class)
            ->add('redacteur',TextType::class,[
                'label'=>'Rédacateur',        
            ])
            ->add('montant',NumberType::class,[
                'label'=>'Montant',        
            ])
            ->add('adresseEmp',TextType::class,[
                'label'=>'Adresse',        
            ])
            ->add('doc',FileType::class,[
                'label'=>'Fichier',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document PDF ',
                    ])
                ],
                'data_class' => null
                        
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}

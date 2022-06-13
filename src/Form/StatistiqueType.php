<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Contrat;
use App\Entity\Recette;
use App\Entity\SousCategorie;
use App\Repository\ContratRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\SousCategorieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StatistiqueType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref', TextType::class, [
                'label' => 'Réference',
                'required' => false,
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('recette', EntityType::class, [
                'class'=>Recette::class,
                'choice_label'=>'libelle',
                'label' => 'Recette',
                'required' => false,
                'placeholder' => 'Selectionner une recette',
            ])
            
            ->add('contrat', EntityType::class, [          
                'class' => Contrat::class,
                'required' => false,
                'choice_label'=>'codeCentre',
                'placeholder' => 'Selectionner un centre'
                
            ])
            ->add('date_depart',DateType::class,[
                'label' => 'Date 1',
                'required' => false,
            ])
            ->add('date_final',DateType::class,[
                'label' => 'Date 2',
                'required' => false,
            ])
            ->add('Recherche',SubmitType::class)

           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'data_class' => SearchData::class,
        ]);
    }


}
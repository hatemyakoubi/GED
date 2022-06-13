<?php

namespace App\Form;

use App\Entity\Acteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identifiant',TextType::class,[
                'label'=>'CIN'
            ])
            ->add('type',TextType::class,[
                'label'=>'Type'
            ])
            ->add('nom',TextType::class,[
                'label'=>'Nom'
            ])
            ->add('prenom',TextType::class,[
                'label'=>'Prénom'
            ])
            //->add('contrat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Acteur::class,
        ]);
    }
}

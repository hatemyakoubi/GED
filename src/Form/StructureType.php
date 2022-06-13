<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Contrat;
use App\Entity\Structure;
use App\Repository\ContratRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class StructureType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->translator = $options['translator'];
        $builder
            ->add('CodeStructure', TextType::class,[
                'label'=>$this->translator->trans('backend.structure.codeStructure')
            ])
            ->add('type', ChoiceType::class,[
                'choices'=>[
                    'Centre'=>'Centre',
                    'Direction génèrale'=>'Direction génèrale',
                    'Recette'=>'Recette',
                    
                ],
                'placeholder'=>'Sélectionnez un type',
                'label'=>$this->translator->trans('backend.structure.type')
            ])
            ->add('lib', TextType::class,[
                'label'=>$this->translator->trans('backend.structure.lib')
            ])
            ->add('etat', NumberType::class,[
                'label'=>$this->translator->trans('backend.structure.etat')
            ])
            // ->add('codeCentre', EntityType::class,[
            //     'class'=>Contrat::class,
            //     'by_reference'=> false,
            //     'query_builder' => function(ContratRepository $c) {
            //         return $c ->createQueryBuilder('c')
            //                 ->orderBy('c.codeCentre', 'ASC');
            //     },
            //     'placeholder'=>'selectionnez un centre',
            //     'choice_label' => 'codeCentre',
            //     //  'multiple'=>true
            //     // 'label'=>$this->translator->trans('backend.structure.codeCentre')
            // ])
            ->add('codeCentre', NumberType::class,[
                'label'=>$this->translator->trans('backend.structure.codeCentre')
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('translator');
        $resolver->setDefaults([
            'data_class' => Structure::class,
        ]);
    }
}

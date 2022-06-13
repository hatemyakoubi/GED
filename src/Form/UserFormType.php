<?php


namespace App\Form;

use App\Entity\User;
use App\Entity\Structure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->translator = $options['translator'];

        $builder
            ->add("username", TextType::class, ["label" => $this->translator->trans('backend.user.username')])
            ->add("cin", NumberType::class, ["label" => $this->translator->trans('backend.user.cin')])
            ->add("nom", TextType::class, ["label" => $this->translator->trans('backend.user.nom')])
            ->add("prenom", TextType::class, ["label" => $this->translator->trans('backend.user.prenom')])
            ->add("justpassword", PasswordType::class, [
                "label" => $this->translator->trans('backend.user.password'),
                "required" => true,
                "mapped" => false,
                "constraints" => [
                    new NotBlank(["message" => $this->translator->trans('backend.global.must_not_be_empty')])
                ]
            ])
            ->add("roles", ChoiceType::class, [
                    'choices'=>[
                    "DGI"=>"ROLE_DGI",
                    "Chef centre"=>"ROLE_CHEF",
                    "Agent de consultaion"=>"ROLE_AGENT_CONSULTATION",
                    "Agent de saisie"=>"ROLE_AGENT_SAISIE",
                ],
                "required" => true,
                "placeholder" => $this->translator->trans('backend.role.choice_role'),
                "constraints" => [
                    new NotBlank(["message" => $this->translator->trans('backend.global.must_not_be_empty')]),
                ]
            ])
            ->add('structure',EntityType::class,[
                'class'=>Structure::class,
                'label'=>'Structure',
                //'choice_label' =>'lib',
                'placeholder'=>'Seléctionnez une structure',
                'choice_label' => function ($structure) {
                    return $structure->getCodeCentre().' '.$structure->getLib();
                },
                 "required" => false,
                
            ])
            ;
            
            // Data transformer
            $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray) {
                     return count($rolesAsArray) ? $rolesAsArray[0]: null;
                },
                function ($rolesAsString) {
                     return [$rolesAsString];
                }
            ));

         
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('translator');
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}

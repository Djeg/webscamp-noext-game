<?php

namespace App\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class RecipeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;

        if (null === $options['user']) {
            $builder
                ->add('createdBy', FormType::class, [
                    'data_class' => 'App\Symfony\Entity\User',
                ])
                ->get('createdBy')
                    ->add('username', TextType::class)
                    ->add('plainPassword', PasswordType::class)
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'App\Symfony\Entity\Recipe',
                'user' => null,
            ])
            ->setAllowedTypes('user', ['null', 'Symfony\Component\Security\Core\User\UserInterface'])
        ;
    }
}

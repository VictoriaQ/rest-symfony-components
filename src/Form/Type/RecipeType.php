<?php

namespace MyApi\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('energy', null)
            ->add('servings', ChoiceType::class, [
                'choices' => [1, 2, 4],
                'invalid_message' => 'Invalid value: Possible choices are 1, 2 or 4',
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyApi\Entity\Recipe'
        ));
    }
}

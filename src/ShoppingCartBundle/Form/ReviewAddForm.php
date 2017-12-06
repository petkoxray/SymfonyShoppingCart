<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewAddForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("body")
            ->add("rating", ChoiceType::class, [
                "placeholder" => "Choose rating:",
                "choices" => [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Review::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'shopping_cart_bundle_review_add_form';
    }
}

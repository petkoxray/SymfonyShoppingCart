<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddPromotionToAllProducts extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('promotion', EntityType::class, [
                "class" => Promotion::class,
                "choice_label" => function ($promotion) {
                    return $promotion;
                },
                "placeholder" => "Select promotion",
                "constraints" => [
                    new NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'shopping_cart_bundle_apply_promotion_to_all_products';
    }
}

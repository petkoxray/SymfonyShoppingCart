<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Promotion;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionAddEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class)
            ->add("discount", IntegerType::class)
            ->add("startDate", DateType::class)
            ->add("endDate", DateType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           "data_class" => Promotion::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'shopping_cart_bundle_promotion_add_edit_form';
    }
}

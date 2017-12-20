<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProductEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("description")
            ->add("price", MoneyType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Product::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'shopping_cart_bundle_user_product_edit_form';
    }
}

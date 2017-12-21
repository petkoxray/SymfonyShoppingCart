<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAddEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name")
            ->add("category", null, [
                "placeholder" => "Select category"
            ])
            ->add("description")
            ->add("imageFile", FileType::class, [
                'required' => true
            ])
            ->add("quantity")
            ->add("price", MoneyType::class)
            ->add("isListed",  ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ],
                'label' => 'Is product listed in the shop?',
                'required' => true])
            ->add("promotions", EntityType::class, [
                "class" => 'ShoppingCartBundle\Entity\Promotion',
                "multiple" => true,
                "expanded" => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Product::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'shopping_cart_bundle_product_add_form';
    }
}

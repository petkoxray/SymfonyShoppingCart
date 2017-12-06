<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryAddEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", null, [
                "label" => "Category name"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Category::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'shopping_cart_bundle_category_add_edit_form';
    }
}

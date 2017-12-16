<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\BannedIP;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BannedIPAddForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("ip", null, [
                "label" => "Full Ip Adress",
                "trim" => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => BannedIP::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'shopping_cart_bundle_blaclist_add_form';
    }
}

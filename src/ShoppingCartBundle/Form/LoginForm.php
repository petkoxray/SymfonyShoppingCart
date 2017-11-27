<?php

namespace ShoppingCartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("_username", TextType::class)
            ->add("_password", PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getBlockPrefix(): string
    {
        return 'shopping_cart_bundle_login_form';
    }
}
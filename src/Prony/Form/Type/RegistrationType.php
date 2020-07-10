<?php

declare(strict_types=1);

namespace Prony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class RegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'talav.form.email', 'translation_domain' => 'TalavUserBundle'])
            ->add('firstName', TextType::class, ['label' => 'prony.form.registration.first_name', 'translation_domain' => 'form'])
            ->add('lastName', TextType::class, ['label' => 'prony.form.registration.last_name', 'translation_domain' => 'form'])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'translation_domain' => 'TalavUserBundle',
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => ['label' => 'talav.form.password'],
                'second_options' => ['label' => 'talav.form.password_confirmation'],
                'invalid_message' => 'talav.password.mismatch',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'talav_user_registration';
    }
}

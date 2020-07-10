<?php

declare(strict_types=1);

namespace Prony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class UpdateTagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'prony.form.tag.name',
                'translation_domain' => 'form',
            ])
            ->add('color', null, [
                'label' => 'prony.form.tag.color',
                'translation_domain' => 'form',
            ])
            ->add('privacy', PrivacyType::class, [
                'label' => 'prony.form.tag.privacy',
                'translation_domain' => 'form',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'prony_tag_update';
    }
}

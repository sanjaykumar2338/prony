<?php

declare(strict_types=1);

namespace Prony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class CreateStatusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'prony.form.status.name',
                'translation_domain' => 'form',
            ])
            ->add('isVoteable', null, [
                'label' => 'prony.form.status.is_voteable',
                'help' => 'prony.form.status.is_voteable_help',
                'translation_domain' => 'form',
            ])
            ->add('color', null, [
                'label' => 'prony.form.status.color',
                'translation_domain' => 'form',
            ])
            ->add('isRoadMap', null, [
                'label' => 'prony.form.status.is_road_map',
                'help' => 'prony.form.status.is_road_map_help',
                'translation_domain' => 'form',
            ])
            ->add('privacy', PrivacyType::class, [
                'label' => 'prony.form.status.privacy',
                'help' => 'prony.form.status.privacy_help',
                'translation_domain' => 'form',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'prony_status_create';
    }
}

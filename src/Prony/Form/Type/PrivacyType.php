<?php

declare(strict_types=1);

namespace Prony\Form\Type;

use Prony\Doctrine\Enum\PrivacyEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrivacyType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => PrivacyEnum::getChoices(),
            'choice_label' => function ($choice, $key, $value) {
                return 'prony.form.privacy.choice.' . strtolower($key);
            },
            'choice_translation_domain' => 'form',
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}

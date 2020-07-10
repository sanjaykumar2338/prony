<?php

declare(strict_types=1);

namespace Prony\Form\Type;

use Prony\Doctrine\Enum\LanguageEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => LanguageEnum::getChoices(),
            'choice_label' => function ($choice, $key, $value) {
                return 'prony.form.language.choice.' . strtolower($key);
            },
            'choice_translation_domain' => 'form',
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}

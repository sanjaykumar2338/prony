<?php

declare(strict_types=1);

namespace Prony\Form\Type\Workspace;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class EditWorkspaceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'prony.form.workspace.name',
                'translation_domain' => 'form',
                'required' => true,
            ])
            ->add('subdomain', null, [
                'label' => 'prony.form.workspace.subdomain',
                'translation_domain' => 'form',
                'required' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'prony_edit_workspace';
    }
}

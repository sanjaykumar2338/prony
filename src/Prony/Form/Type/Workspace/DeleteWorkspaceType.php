<?php

declare(strict_types=1);

namespace Prony\Form\Type\Workspace;

use Prony\Entity\Workspace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class DeleteWorkspaceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Workspace $workspace */
        $workspace = $builder->getData();
        $builder
            ->add('workspace_name', null, [
                'label' => 'prony.form.workspace.name',
                'translation_domain' => 'form',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\EqualTo([
                        'value' => $workspace->getName(),
                        'message' => 'prony.workspace.delete.wrong_name',
                    ]),
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'prony_delete_workspace';
    }
}

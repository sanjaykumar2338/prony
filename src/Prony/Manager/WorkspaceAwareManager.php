<?php

declare(strict_types=1);

namespace Prony\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Prony\Model\WorkspaceAware;
use Prony\Provider\WorkspaceProvider;
use Symfony\Contracts\Translation\TranslatorInterface;
use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Manager\ResourceManager;
use Talav\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

abstract class WorkspaceAwareManager extends ResourceManager
{
    /** @var WorkspaceProvider */
    protected $workspaceProvider;

    protected TranslatorInterface $translator;

    public function __construct($className, EntityManagerInterface $em, FactoryInterface $factory, WorkspaceProvider $workspaceProvider, TranslatorInterface $translator)
    {
        parent::__construct($className, $em, $factory);
        $this->workspaceProvider = $workspaceProvider;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, WorkspaceAware::class);
        if (null !== ($workspace = $this->workspaceProvider->getWorkspace())) {
            $resource->setWorkspace($workspace);
        }
        parent::add($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, WorkspaceAware::class);
        $workspace = $resource->getWorkspace();
        parent::remove($resource);
    }
}

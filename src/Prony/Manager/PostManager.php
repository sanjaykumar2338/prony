<?php

declare(strict_types=1);

namespace Prony\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Prony\Entity\Post;
use Prony\Provider\WorkspaceProvider;
use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Manager\ResourceManager;
use Talav\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

final class PostManager extends ResourceManager
{
    /** @var WorkspaceProvider */
    protected $workspaceProvider;

    public function __construct($className, EntityManagerInterface $em, FactoryInterface $factory, WorkspaceProvider $workspaceProvider)
    {
        parent::__construct($className, $em, $factory);
        $this->workspaceProvider = $workspaceProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Post::class);
        if (($workspace = $this->workspaceProvider->getWorkspace()) && null === $resource->getStatus()) {
            $resource->setStatus($workspace->getPostInitialStatus());
        }
        parent::add($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResourceInterface $resource, $flush = false): void
    {
        Assert::isInstanceOf($resource, Post::class);
        parent::update($resource, $flush);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Post::class);
        parent::remove($resource);
    }
}

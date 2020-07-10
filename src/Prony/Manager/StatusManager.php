<?php

declare(strict_types=1);

namespace Prony\Manager;

use Prony\Entity\Status;
use Talav\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

final class StatusManager extends WorkspaceAwareManager
{
    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Status::class);
        parent::add($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResourceInterface $resource, $flush = false): void
    {
        Assert::isInstanceOf($resource, Status::class);
        if (null !== $resource->getWorkspace()) {
            $resource->getWorkspace()->normalizeStatuses($resource);
        }
        parent::update($resource, $flush);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Status::class);
        parent::remove($resource);
    }
}

<?php

declare(strict_types=1);

namespace Prony\Manager;

use Prony\Entity\Tag;
use Talav\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

final class TagManager extends WorkspaceAwareManager
{
    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Tag::class);
        parent::add($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResourceInterface $resource, $flush = false): void
    {
        Assert::isInstanceOf($resource, Tag::class);
        parent::update($resource, $flush);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Tag::class);
        parent::remove($resource);
    }
}

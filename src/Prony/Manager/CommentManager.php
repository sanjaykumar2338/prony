<?php

declare(strict_types=1);

namespace Prony\Manager;

use Prony\Entity\Comment;
use Talav\Component\Resource\Manager\ResourceManager;
use Talav\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

final class CommentManager extends ResourceManager
{
    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Comment::class);
        parent::add($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResourceInterface $resource, $flush = false): void
    {
        Assert::isInstanceOf($resource, Comment::class);
        parent::update($resource, $flush);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Comment::class);
        parent::remove($resource);
    }
}

<?php

declare(strict_types=1);

namespace Prony\Manager;

use Prony\Entity\Board;
use Talav\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

final class BoardManager extends WorkspaceAwareManager
{
    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Board::class);
        parent::add($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResourceInterface $resource, $flush = false): void
    {
        Assert::isInstanceOf($resource, Board::class);
        if (null !== $resource->getWorkspace()) {
            $resource->getWorkspace()->normalizeBoards($resource);
        }
        parent::update($resource, $flush);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, Board::class);
        parent::remove($resource);
    }

    /**
     * @return Board[]
     */
    public function findAllBoard()
    {
        return $this->getRepository()->findBy(['workspace' => $this->workspaceProvider->getWorkspace()], ['position' => 'ASC']);
    }
}

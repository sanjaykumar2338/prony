<?php

declare(strict_types=1);

namespace Prony\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Prony\Entity\Tag;
use Talav\Component\Resource\Manager\ManagerInterface;

final class TagDataPersister implements ContextAwareDataPersisterInterface
{
    /** @var ManagerInterface */
    private $tagManager;

    public function __construct(ManagerInterface $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Tag;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data, array $context = [])
    {
        $this->tagManager->update($data, true);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->tagManager->remove($data);
    }
}

<?php

declare(strict_types=1);

namespace Prony\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Prony\Entity\Post;
use Talav\Component\Resource\Manager\ManagerInterface;

final class PostDataPersister implements ContextAwareDataPersisterInterface
{
    /** @var ManagerInterface */
    private $postManager;

    public function __construct(ManagerInterface $postManager)
    {
        $this->postManager = $postManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Post;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data, array $context = [])
    {
        $this->postManager->update($data, true);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->postManager->remove($data);
    }
}

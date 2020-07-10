<?php

declare(strict_types=1);

namespace Prony\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Prony\Entity\Board;
use Talav\Component\Resource\Manager\ManagerInterface;

final class BoardDataPersister implements ContextAwareDataPersisterInterface
{
    /** @var ManagerInterface */
    private $boardManager;

    public function __construct(ManagerInterface $boardManager)
    {
        $this->boardManager = $boardManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Board;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data, array $context = [])
    {
        $this->boardManager->update($data, true);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->boardManager->remove($data);
    }
}

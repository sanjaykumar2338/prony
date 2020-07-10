<?php

declare(strict_types=1);

namespace Prony\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Prony\Entity\Status;
use Talav\Component\Resource\Manager\ManagerInterface;

final class StatusDataPersister implements ContextAwareDataPersisterInterface
{
    /** @var ManagerInterface */
    private $statusManager;

    public function __construct(ManagerInterface $statusManager)
    {
        $this->statusManager = $statusManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Status;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data, array $context = [])
    {
        $this->statusManager->update($data, true);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->statusManager->remove($data);
    }
}

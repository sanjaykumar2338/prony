<?php

declare(strict_types=1);

namespace Prony\Provider;

use Prony\Entity\Workspace;

class WorkspaceProvider
{
    /** @var Workspace|null */
    private $workspace;

    public function getWorkspace(): ?Workspace
    {
        return $this->workspace;
    }

    public function setWorkspace(Workspace $workspace): void
    {
        $this->workspace = $workspace;
    }

    public function hasWorkspace(): bool
    {
        return null !== $this->workspace;
    }
}

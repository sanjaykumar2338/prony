<?php

declare(strict_types=1);

namespace Prony\Model;

use Prony\Entity\Workspace;

interface WorkspaceAware
{
    public function getWorkspace(): ?Workspace;

    public function setWorkspace(Workspace $workspace): void;
}

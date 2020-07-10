<?php

declare(strict_types=1);

namespace Prony\Request\ParamConverter;

use Prony\Entity\Workspace;
use Prony\Provider\WorkspaceProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

final class WorkspaceConverter implements ParamConverterInterface
{
    /** @var WorkspaceProvider */
    private $workspaceProvider;

    public function __construct(WorkspaceProvider $workspaceProvider)
    {
        $this->workspaceProvider = $workspaceProvider;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $request->attributes->set($configuration->getName(), $this->workspaceProvider->getWorkspace());
    }

    public function supports(ParamConverter $configuration)
    {
        return Workspace::class == $configuration->getClass();
    }
}

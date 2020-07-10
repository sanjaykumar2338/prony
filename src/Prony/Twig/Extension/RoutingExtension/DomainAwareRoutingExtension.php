<?php

declare(strict_types=1);

namespace Prony\Twig\Extension\RoutingExtension;

use Prony\Provider\WorkspaceProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;

/**
 * Adds domain parameter to all routes
 *
 * @see https://stackoverflow.com/questions/25232509/symfony-match-route-with-dynamic-default-subdomain
 */
final class DomainAwareRoutingExtension extends AbstractExtension
{
    /** @var WorkspaceProvider */
    private $workspaceProvider;

    public function __construct(WorkspaceProvider $workspaceProvider, UrlGeneratorInterface $generator)
    {
        $this->workspaceProvider = $workspaceProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($name, $parameters = [], $relative = false)
    {
        if ($workspace = $this->workspaceProvider->getWorkspace()) {
            $parameters['domain'] = $workspace->getDomain();
        }

        return parent::getPath($name, $parameters, $relative);
    }
}

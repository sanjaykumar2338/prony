<?php

declare(strict_types=1);

namespace Prony\EventListener;

use Prony\Provider\WorkspaceProvider;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Talav\Component\Resource\Manager\ManagerInterface;

final class CurrentWorkspaceListener
{
    /** @var ManagerInterface */
    private $workspaceManager;

    /** @var WorkspaceProvider */
    private $workspaceProvider;

    /** @var string */
    private $domain;

    public function __construct(
        string $domain,
        ManagerInterface $workspaceManager,
        WorkspaceProvider $workspaceProvider
    ) {
        $this->workspaceManager = $workspaceManager;
        $this->workspaceProvider = $workspaceProvider;
        $this->domain = $domain;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $parameter = 'domain';
        $domain = str_replace('www.', '', $request->getHost());
        // main website
        if ($domain == $this->domain) {
            return;
        }
        if (strpos($domain, $this->domain)) {
            // subdomain
            $domain = explode('.', str_replace($this->domain, '', $domain))[0];
            $parameter = 'subdomain';
        }

        $workspace = $this->workspaceManager->getRepository()->findOneBy([$parameter => $domain]);
        if (null === $workspace) {
            throw new NotFoundHttpException(sprintf(
                'No workspace for host parameters "%s" => "%s"', $parameter, $domain
            ));
        }
        $this->workspaceProvider->setWorkspace($workspace);
    }
}

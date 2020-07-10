<?php

declare(strict_types=1);

namespace Talav\EventListener\Tests;

use AppBundle\Entity\Media;
use PHPUnit\Framework\TestCase;
use Prony\Entity\Workspace;
use Prony\EventListener\CurrentWorkspaceListener;
use Prony\Provider\WorkspaceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;

final class CurrentWorkspaceListenerTest extends TestCase
{
    protected WorkspaceProvider $provider;

    /**
     * @before
     */
    public function before()
    {
        $this->provider = new WorkspaceProvider();
    }

    /**
     * @test
     */
    public function it_correctly_skips_main_website()
    {
        $domain = "www.prony.local";
        $request = $this->createMock(Request::class);
        $event = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);
        $request->method('getHost')->willReturn($domain);

        $manager = $this->createMock(ManagerInterface::class);
        $listener = new CurrentWorkspaceListener("prony.local", $manager, $this->provider);
        self::assertNull($listener->onKernelRequest($event));
    }

    /**
     * @test
     */
    public function it_correctly_defines_workspace_for_subdomain()
    {
        $domain = "test1.prony.local";
        $params = ["subdomain" => "test1"];
        $return = new Workspace();
        $this->createMocks($domain, $params, $return);
        self::assertNotNull($this->provider->getWorkspace());
    }

    /**
     * @test
     */
    public function it_correctly_defines_workspace_for_random_domain()
    {
        $domain = "test1.com";
        $params = ["domain" => "test1.com"];
        $return = new Workspace();
        $this->createMocks($domain, $params, $return);
        self::assertNotNull($this->provider->getWorkspace());
    }

    /**
     * @test
     */
    public function it_correctly_defines_workspace_for_random_domain_with_www()
    {
        $domain = "www.test1.com";
        $params = ["domain" => "test1.com"];
        $return = new Workspace();
        $this->createMocks($domain, $params, $return);
        self::assertNotNull($this->provider->getWorkspace());
    }

    /**
     * @test
     */
    public function it_throws_exception_for_not_found_domains()
    {
        $this->expectException(NotFoundHttpException::class);
        $domain = "test1.com";
        $params = ["domain" => "test1.com"];
        $this->createMocks($domain, $params, null);
    }

    public function createMocks(string $domain, array $params, ?Workspace $return = null)
    {
        $manager = $this->createMock(ManagerInterface::class);
        $repository = $this->createMock(RepositoryInterface::class);

        $manager->method('getRepository')->willReturn($repository);
        $repository->method('findOneBy')->with($params)->willReturn($return);

        $request = $this->createMock(Request::class);
        $event = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);
        $request->method('getHost')->willReturn($domain);

        $listener = new CurrentWorkspaceListener("prony.local", $manager, $this->provider);
        $listener->onKernelRequest($event);
    }
}
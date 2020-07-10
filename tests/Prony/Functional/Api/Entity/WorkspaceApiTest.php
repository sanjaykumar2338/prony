<?php

namespace Prony\Tests\Functional\Api\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Prony\Entity\Board;
use Prony\Entity\Workspace;
use Prony\PhpUnit\CreateDatabaseTrait;

class WorkspaceApiTest extends ApiTestCase
{
    /**
     * @test
     */
    public function it_gets_workspace_by_id(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $iri = static::findIriBy(Workspace::class, ['name' => 'Workspace 1']);
        $response = $client->request('GET', $iri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            "@context" => "/api/contexts/Workspace",
            "@type" => "Workspace",
            "name" => "Workspace 1",
        ]);
    }
}
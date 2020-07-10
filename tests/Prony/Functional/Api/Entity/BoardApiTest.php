<?php

namespace Prony\Tests\Functional\Api\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Prony\Entity\Board;
use Prony\PhpUnit\CreateDatabaseTrait;

class BoardApiTest extends ApiTestCase
{
    /**
     * @test
     */
    public function it_gets_collection(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('GET', '/api/boards');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Board',
            '@id' => '/api/boards',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                0 => [
                    "@type" => "Board",
                    "name" => "Board 1",
                    "position" => 0,
                    "slug" => "board-1",
                    "workspace" => [
                        "@type" => "Workspace",
                        "name" => "Workspace 1"
                    ]
                ],
                1 => [
                    "@type" => "Board",
                    "name" => "Board 2",
                    "position" => 1,
                    "slug" => "board-2",
                    "workspace" => [
                        "@type" => "Workspace",
                        "name" => "Workspace 1"
                    ],
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_creates_new_board(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/boards', ['json' => [
            'name' => 'Board 10',
            'position' => 0,
        ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Board',
            '@type' => 'Board',
            'name' => 'Board 10',
        ]);
        $this->assertRegExp('~^/api/boards/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Board::class);

        $response = $client->request('GET', '/api/boards');
        $this->assertEquals(4, $response->toArray()['hydra:totalItems']);
    }

    /**
     * @test
     */
    public function it_shows_errors_for_invalid_input(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/boards', ['json' => [
            'position' => -1
        ]]);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            "@context" => "/api/contexts/ConstraintViolationList",
            "@type" => "ConstraintViolationList",
            "hydra:title" => "An error occurred",
            "violations" => [
                0 => [
                    "propertyPath" => "name",
                    "message" => "This value should not be null.",
                ],
                1 => [
                    "propertyPath" => "position",
                    "message" => "This value should be greater than or equal to 0."
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_create_boards_with_duplicate_names(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $client->request('POST', '/api/boards', ['json' => [
            'name' => 'Board 1'
        ]]);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            "@context" => "/api/contexts/ConstraintViolationList",
            "@type" => "ConstraintViolationList",
            "hydra:title" => "An error occurred",
            "violations" => [
                0 => [
                    "propertyPath" => "name",
                    "message" => "This value is already used.",
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_allows_to_update_board_name(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $iri = static::findIriBy(Board::class, ['name' => 'Board 2']);
        $client->request('PUT', $iri, ['json' => [
            'name' => 'Board 2 updated',
        ]]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'name' => 'Board 2 updated',
        ]);
    }

    /**
     * @test
     */
    public function it_allows_to_update_board_position(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('GET', '/api/boards');
        $this->assertResponseIsSuccessful();
        $responseArray = $response->toArray();
        $this->assertEquals('Board 1', $responseArray['hydra:member'][0]['name']);
        $this->assertEquals(0, $responseArray['hydra:member'][0]['position']);
        $this->assertEquals('Board 2', $responseArray['hydra:member'][1]['name']);
        $this->assertEquals(1, $responseArray['hydra:member'][1]['position']);

        // move board 1 to 0 position
        $iri = static::findIriBy(Board::class, ['name' => 'Board 2']);
        $client->request('PUT', $iri, ['json' => [
            'position' => 0,
        ]]);

        // check updated
        $response = $client->request('GET', '/api/boards');
        $responseArray = $response->toArray();
        $this->assertEquals('Board 2', $responseArray['hydra:member'][0]['name']);
        $this->assertEquals(0, $responseArray['hydra:member'][0]['position']);
        $this->assertEquals('Board 1', $responseArray['hydra:member'][1]['name']);
        $this->assertEquals(1, $responseArray['hydra:member'][1]['position']);
    }

    /**
     * @test
     */
    public function it_deletes_board(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        // 3 boards before
        $response = $client->request('GET', '/api/boards');
        $this->assertEquals(3, $response->toArray()['hydra:totalItems']);
        $iri = static::findIriBy(Board::class, ['name' => 'Board 2']);
        $client->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(204);
        // 2 boards after
        $response = $client->request('GET', '/api/boards');
        $this->assertEquals(2, $response->toArray()['hydra:totalItems']);
    }
}
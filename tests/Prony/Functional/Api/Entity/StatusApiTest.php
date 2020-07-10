<?php

namespace Prony\Tests\Functional\Api\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Prony\Entity\Board;
use Prony\Entity\Status;
use Prony\Entity\Tag;
use Prony\PhpUnit\CreateDatabaseTrait;

class StatusApiTest extends ApiTestCase
{
    /**
     * @test
     */
    public function it_returns_collection(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('GET', "/api/statuses");
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            "@context" => "/api/contexts/Status",
            "@type" => "hydra:Collection",
            "hydra:member" => [
                0 => [
                    "@type" => "Status",
                    "name" => "Open",
                    "position" => 0,
                ],
                1 => [
                    "@type" => "Status",
                    "name" => "Under Review",
                    "position" => 1,
                ],
                2 => [
                    "@type" => "Status",
                    "name" => "Planned",
                    "position" => 2,
                ],
                3 => [
                    "@type" => "Status",
                    "name" => "In Progress",
                    "position" => 3,
                ],
                4 => [
                    "@type" => "Status",
                    "name" => "Complete",
                    "position" => 4,
                ],
                5 => [
                    "@type" => "Status",
                    "name" => "Closed",
                    "position" => 5,
                ]
            ],
            "hydra:totalItems" => 6
        ]);
    }

    /**
     * @test
     */
    public function it_creates_new_status(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/statuses', ['json' => [
            'name' => 'New status',
            'privacy' => "public",
        ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Status',
            '@type' => 'Status',
            'name' => 'New status',
        ]);
        $this->assertRegExp('~^/api/statuses/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Board::class);

        $response = $client->request('GET', "/api/statuses");
        $this->assertEquals(7, $response->toArray()['hydra:totalItems']);
    }

    /**
     * @test
     */
    public function it_shows_errors_for_invalid_input(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/statuses', ['json' => [
            'privacy' => 'incorrect',
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
                    "propertyPath" => "privacy",
                    "message" => "The value you selected is not a valid choice.",
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_create_statuses_for_the_same_board_with_duplicate_names(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/statuses', ['json' => [
            'name' => 'Open',
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
    public function it_updates_status(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('GET', "/api/statuses");
        $iri = $response->toArray()['hydra:member'][1]['@id'];
        $client->request('PUT', $iri, ['json' => [
            'name' => 'Under Review updated',
            'privacy' => 'private'
        ]]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'name' => 'Under Review updated',
        ]);
    }

    /**
     * @test
     */
    public function it_deletes_status(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        // 6 statuses before
        $response = $client->request('GET', "/api/statuses");
        $this->assertEquals(6, $response->toArray()['hydra:totalItems']);
        $client->request('DELETE', $response->toArray()['hydra:member'][0]['@id']);
        $this->assertResponseStatusCodeSame(204);
        // 5 statuses after
        $response = $client->request('GET', "/api/statuses");
        $this->assertEquals(5, $response->toArray()['hydra:totalItems']);
    }
}
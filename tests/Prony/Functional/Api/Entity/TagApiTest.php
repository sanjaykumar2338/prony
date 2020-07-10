<?php

namespace Prony\Tests\Functional\Api\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Prony\Entity\Board;
use Prony\Entity\Company;
use Prony\Entity\Tag;

class TagApiTest extends ApiTestCase
{
    /**
     * @test
     */
    public function it_gets_collection(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('GET', "api/tags");
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            "@context" => "/api/contexts/Tag",
            "@type" => "hydra:Collection",
            "hydra:member" => [
                0 => [
                    "@type" => "Tag",
                    "name" => "Bug",
                    "slug" => "bug",
                    "privacy" => "public",
                ],
                1 => [
                    "@type" => "Tag",
                    "name" => "Feature",
                    "slug" => "feature",
                    "privacy" => "public",
                ],
                2 => [
                    "@type" => "Tag",
                    "name" => "Must Have",
                    "slug" => "must-have",
                    "privacy" => "public",
                ]
            ],
            "hydra:totalItems" => 3
        ]);
    }

    /**
     * @test
     */
    public function it_creates_new_tag(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/tags', ['json' => [
            'name' => 'New tag',
            'privacy' => "public"
        ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Tag',
            '@type' => 'Tag',
            'name' => 'New tag',
        ]);
        $this->assertRegExp('~^/api/tags/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Board::class);

        $response = $client->request('GET', "/api/tags");
        $this->assertEquals(4, $response->toArray()['hydra:totalItems']);
    }

    /**
     * @test
     */
    public function it_shows_errors_for_invalid_input(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/tags', ['json' => [
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
    public function it_does_not_allow_to_create_tags_for_the_same_board_with_duplicate_names(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/tags', ['json' => [
            'name' => 'Bug',
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
    public function it_allows_to_replace_tag_values(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $iri = static::findIriBy(Tag::class, ['name' => 'Feature']);
        $client->request('PUT', $iri, ['json' => [
            'name' => 'Feature updated',
            'privacy' => 'private',
            'color' => '#FFFFFF',
        ]]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'name' => 'Feature updated',
        ]);
    }

    /**
     * @test
     */
    public function it_deletes_tag(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        // 3 tags before
        $response = $client->request('GET', "/api/tags");
        $this->assertEquals(3, $response->toArray()['hydra:totalItems']);
        $client->request('DELETE', $response->toArray()['hydra:member'][0]['@id']);
        $this->assertResponseStatusCodeSame(204);
        // 2 tags after
        $response = $client->request('GET', "/api/tags");
        $this->assertEquals(2, $response->toArray()['hydra:totalItems']);
    }
}
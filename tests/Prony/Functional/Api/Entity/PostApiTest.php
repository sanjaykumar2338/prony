<?php

namespace Prony\Tests\Functional\Api\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Prony\Entity\Board;
use Prony\Entity\Post;
use Prony\Entity\Status;
use Prony\Entity\Tag;
use Prony\PhpUnit\CreateDatabaseTrait;

class PostApiTest extends ApiTestCase
{
    /**
     * @var Generator
     */
    private $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = FakerFactory::create();
    }

    /**
     * @test
     */
    public function it_creates_new_post_with_correct_status(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/posts', ['json' => [
            'title' => $this->faker->text(100),
            'description' => $this->faker->text(),
            'extra' => ["field1" => $this->faker->text()],
            'board' => static::findIriBy(Board::class, ['name' => 'Board 2'])
        ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Post',
            '@type' => 'Post',
            'voteCount' => 0,
            'commentCount' => 0
        ]);
        $this->assertRegExp('~^/api/posts/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Post::class);

        $response = $client->request('GET', $response->toArray()['@id']);
        $this->assertJsonContains([
            "status" => [
                "@type" => "Status",
                "name" => "Open",
                "position" => 0,
                "privacy" => "public",
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_shows_errors_for_invalid_create_input(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $response = $client->request('POST', '/api/posts', ['json' => [
            'extra' => ['3'],
        ]]);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            "@context" => "/api/contexts/ConstraintViolationList",
            "@type" => "ConstraintViolationList",
            "hydra:title" => "An error occurred",
            "violations" => [
                0 => [
                    "propertyPath" => "title",
                    "message" => "This value should not be null."
                ],
                1 => [
                    "propertyPath" => "description",
                    "message" => "This value should not be null."
                ],
                2 => [
                    "propertyPath" => "extra",
                    "message" => "Extra post data should be in a key-value format."
                ],
                3 => [
                    "propertyPath" => "board",
                    "message" => "This value should not be null."
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_updates_post(): void
    {
        $client = static::createClient([], ['base_uri' => 'http://w1.prony.local:8000/']);
        $post = self::$container->get('app.manager.post')->getRepository()->findAll()[0];
        $uri = static::findIriBy(Post::class, ['id' => $post->getId()]);
        $newTitle =  $this->faker->text(100);
        $newDescription = $this->faker->text(200);
        $newField1 = $this->faker->text(200);

        $status = $post->getBoard()->getWorkspace()->getStatuses()[4];
        $client->request('PUT', $uri, ['json' => [
            'title' => $newTitle,
            'description' => $newDescription,
            'extra' => ["field1" => $newField1],
            'status' => '/api/statuses/' . $status->getId()
        ]]);
        $response = $client->request('GET', $uri);
        $this->assertJsonContains([
            "@context" => "/api/contexts/Post",
            "@type" => "Post",
            "title" => $newTitle,
            "description" => $newDescription,
            "extra" => [
                "field1" => $newField1,
            ],
            "score" => 0,
            "voteCount" => 0,
            "commentCount" => 0,
            "status" => [
                "@type" => "Status",
                "name" => $status->getName(),
            ]
        ]);
    }
}
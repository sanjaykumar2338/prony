<?php

declare(strict_types=1);

namespace Prony\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Prony\Entity\Board;
use Prony\Entity\Media;
use Prony\Entity\Post;
use Prony\Entity\Status;
use Prony\Entity\User;
use Prony\Entity\Workspace;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\User\Manager\UserManagerInterface;

class PostFixtures extends Fixture implements OrderedFixtureInterface
{
    /** @var ManagerInterface */
    private $boardManager;

    /** @var ManagerInterface */
    private $tagManager;

    /** @var ManagerInterface */
    private $statusManager;

    /** @var ManagerInterface */
    private $workspaceManager;

    /** @var ManagerInterface */
    private $postManager;

    /** @var UserManagerInterface */
    private $userManager;

//    /** @var ManagerInterface */
//    private $mediaManager;

    /** @var Generator */
    private $faker;

    private $mediaCache = [];

    private $userCache = [];

    public function __construct(
        ManagerInterface $workspaceManager,
        ManagerInterface $boardManager,
        ManagerInterface $statusManager,
        ManagerInterface $postManager,
        ManagerInterface $tagManager,
//        ManagerInterface $mediaManager,
        UserManagerInterface $userManager)
    {
        $this->workspaceManager = $workspaceManager;
        $this->boardManager = $boardManager;
        $this->postManager = $postManager;
        $this->tagManager = $tagManager;
        $this->statusManager = $statusManager;
        $this->userManager = $userManager;
//        $this->mediaManager = $mediaManager;
        $this->faker = FakerFactory::create();
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }

    public function load(ObjectManager $manager)
    {
        $workspace = $this->getWorkspace();
        for ($i = 0; $i < 100; ++$i) {
            /** @var Post $post */
            $post = $this->postManager->create();
            $post->setBoard($this->getRandomBoard($workspace));
            $post->setStatus($this->getRandomStatus($workspace, 0.5));
            $post->setTitle($this->faker->sentence(10));
            $post->setDescription($this->faker->realText(rand(800, 2000)));
            $user = $this->getRandomUser();
            $post->setCreatedBy($user);
            $post->setUpdatedBy($user);
            $post->setCreatedAt($this->faker->dateTimeInInterval('-30 days'));
//            if ($media = $this->getRandomImage(0.7)) {
//                $post->setMedia($media);
//            }

            $this->postManager->update($post, true);
        }
    }

    private function getWorkspace($name = 'Workspace 1'): Workspace
    {
        return $this->workspaceManager->getRepository()->findOneBy(['name' => $name]);
    }

    private function getRandomBoard(Workspace $workspace): Board
    {
        return $workspace->getBoards()[array_rand($workspace->getBoards()->toArray())];
    }

    private function getRandomStatus(Workspace $workspace, $probability): ?Status
    {
        if (rand(0, 1) < $probability) {
            return null;
        }

        return $workspace->getStatuses()[array_rand($workspace->getStatuses()->toArray())];
    }

    private function getRandomUser(): User
    {
        if (0 == count($this->userCache)) {
            $this->userCache = $this->userManager->getRepository()->findAll();
        }

        return $this->userCache[array_rand($this->userCache)];
    }

    private function getRandomImage($probability): ?Media
    {
        if (rand(0, 1) < $probability) {
            return null;
        }
        if (0 == count($this->mediaCache)) {
            $this->mediaCache = $this->mediaManager->getRepository()->findAll();
        }
        shuffle($this->mediaCache);

        return array_shift($this->mediaCache);
    }
}

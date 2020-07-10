<?php

declare(strict_types=1);

namespace Prony\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Prony\Entity\Comment;
use Prony\Entity\Post;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\User\Manager\UserManagerInterface;

class CommentFixtures extends Fixture implements OrderedFixtureInterface
{
    /** @var ManagerInterface */
    private $postManager;

    /** @var ManagerInterface */
    private $commentManager;

    /** @var UserManagerInterface */
    private $userManager;

    /** @var Generator */
    private $faker;

    private $postCache = [];

    private $userCache = [];

    public function __construct(
        ManagerInterface $postManager,
        ManagerInterface $commentManager,
        UserManagerInterface $userManager)
    {
        $this->postManager = $postManager;
        $this->commentManager = $commentManager;
        $this->userManager = $userManager;
        $this->faker = FakerFactory::create();
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 11;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getPosts() as $post) {
            $this->createRootComment($post);
        }
        $this->postManager->flush();
        for ($i = 0; $i < 5; ++$i) {
            for ($j = 1; $j <= rand(50, 100); ++$j) {
                $this->createComment($i);
            }
            $this->commentManager->flush();
        }
    }

    private function getPosts(): array
    {
        return $this->postManager->getRepository()->findAll();
    }

    private function createRootComment(Post $post): void
    {
        $users = $this->getUsers();
        /** @var Comment $comment */
        $comment = $this->commentManager->create();
        $comment->setComment('Root comment for ' . $post->getTitle());
        $comment->setCreatedBy($users[array_rand($users)]);
        $comment->setUpdatedBy($users[array_rand($users)]);
        $post->setRootComment($comment);
//        if ($media = $this->getRandomImage(0.7)) {
//            $comment->setMedia($media);
//        }
        $this->commentManager->update($comment);
    }

    private function createComment($level): void
    {
        $users = $this->getUsers();
        $comments = $this->getComments($level);
        /** @var Comment $comment */
        $comment = $this->commentManager->create();
        $comment->setComment($this->faker->text());
        $comment->setParent($comments[array_rand($comments)]);
        $comment->setCreatedBy($users[array_rand($users)]);
        $comment->setUpdatedBy($users[array_rand($users)]);
//        if ($media = $this->getRandomImage(0.2)) {
//            $comment->setMedia($media);
//        }
        $this->commentManager->update($comment);
    }

    private function getComments($level): array
    {
        if (!isset($this->postCache[$level])) {
            $this->postCache[$level] = $this->commentManager->getRepository()->findBy(['level' => $level]);
        }

        return $this->postCache[$level];
    }

    private function getUsers(): array
    {
        if (0 == count($this->userCache)) {
            $this->userCache = $this->userManager->getRepository()->findAll();
        }

        return $this->userCache;
    }

//    private function getRandomImage($probability): ?Media
//    {
//        if (rand(0, 1) < $probability) {
//            return null;
//        }
//        if (null === $this->mediaCache) {
//            $this->mediaCache = $this->mediaManager->getRepository()->findAll();
//        }
//        shuffle($this->mediaCache);
//
//        return array_shift($this->mediaCache);
//    }
}

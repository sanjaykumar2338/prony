<?php

declare(strict_types=1);

namespace Prony\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Prony\Doctrine\Enum\PrivacyEnum;
use Prony\Entity\Tag;
use Prony\Entity\Workspace;
use Talav\Component\Resource\Manager\ManagerInterface;

class TagFixtures extends Fixture implements OrderedFixtureInterface
{
    /** @var ManagerInterface */
    private $workspaceManager;

    /** @var ManagerInterface */
    private $tagManager;

    /** @var Generator */
    private $faker;

    public function __construct(ManagerInterface $workspaceManager, ManagerInterface $tagManager)
    {
        $this->workspaceManager = $workspaceManager;
        $this->tagManager = $tagManager;
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        /** @var Tag $tag */
        $tag = $this->tagManager->create();
        $tag->setName('Must Have');
        $tag->setPrivacy(PrivacyEnum::PUBLIC);
        $tag->setColor('fbc02d');
        $tag->setWorkspace($this->getWorkspace());
        $this->tagManager->update($tag);

        $tag = $this->tagManager->create();
        $tag->setName('Feature');
        $tag->setPrivacy(PrivacyEnum::PUBLIC);
        $tag->setColor('48a999');
        $tag->setWorkspace($this->getWorkspace());
        $this->tagManager->update($tag);

        $tag = $this->tagManager->create();
        $tag->setName('Bug');
        $tag->setPrivacy(PrivacyEnum::PUBLIC);
        $tag->setColor('ff94c2');
        $tag->setWorkspace($this->getWorkspace());
        $this->tagManager->update($tag);

        $tag = $this->tagManager->create();
        $tag->setName('Bug');
        $tag->setPrivacy(PrivacyEnum::PUBLIC);
        $tag->setColor('ff94c2');
        $tag->setWorkspace($this->getWorkspace('Workspace 2'));

        $this->tagManager->update($tag, true);
    }

    public function getWorkspace($name = 'Workspace 1'): Workspace
    {
        return $this->workspaceManager->getRepository()->findOneBy(['name' => $name]);
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}

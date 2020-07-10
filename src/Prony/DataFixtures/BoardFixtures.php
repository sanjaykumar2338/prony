<?php

declare(strict_types=1);

namespace Prony\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Prony\Entity\Board;
use Prony\Entity\User;
use Prony\Entity\Workspace;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\User\Manager\UserManagerInterface;

class BoardFixtures extends Fixture implements OrderedFixtureInterface
{
    /** @var ManagerInterface */
    private $boardManager;

    /** @var ManagerInterface */
    private $workspaceManager;

    /** @var UserManagerInterface */
    private $userManager;

    /** @var Generator */
    private $faker;

    public function __construct(ManagerInterface $boardManager, ManagerInterface $workspaceManager, UserManagerInterface $userManager)
    {
        $this->boardManager = $boardManager;
        $this->workspaceManager = $workspaceManager;
        $this->userManager = $userManager;
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        /** @var Board $board */
        $board = $this->boardManager->create();
        $board->setName('Board 1');
        $board->setWorkspace($this->getWorkspace());
        $board->setCreatedBy($this->getUser());
        $board->setUpdatedBy($this->getUser());
        $this->boardManager->update($board);

        /** @var Board $board */
        $board = $this->boardManager->create();
        $board->setName('Board 2');
        $board->setWorkspace($this->getWorkspace());
        $board->setCreatedBy($this->getUser());
        $board->setUpdatedBy($this->getUser());
        $this->boardManager->update($board);

        /** @var Board $board */
        $board = $this->boardManager->create();
        $board->setName('Board 3');
        $board->setWorkspace($this->getWorkspace());
        $board->setCreatedBy($this->getUser());
        $board->setUpdatedBy($this->getUser());
        $this->boardManager->update($board);

        // Create board with the same name for another workspace to test sluggable behavior
        /** @var Board $board */
        $board = $this->boardManager->create();
        $board->setName('Board 1');
        $board->setWorkspace($this->getWorkspace('w2'));
        $board->setCreatedBy($this->getUser());
        $board->setUpdatedBy($this->getUser());
        $this->boardManager->update($board, true);
    }

    public function getUser(): User
    {
        return $this->userManager->findUserByUsername('tester1');
    }

    public function getWorkspace($domain = 'w1'): Workspace
    {
        return $this->workspaceManager->getRepository()->findOneBy(['subdomain' => $domain]);
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}

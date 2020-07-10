<?php

declare(strict_types=1);

namespace Prony\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Prony\Doctrine\Enum\LanguageEnum;
use Prony\Entity\User;
use Prony\Entity\Workspace;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\User\Manager\UserManagerInterface;

class WorkspaceFixtures extends Fixture implements OrderedFixtureInterface
{
    /** @var ManagerInterface */
    private $workspaceManager;

    /** @var UserManagerInterface */
    private $userManager;

    /** @var Generator */
    private $faker;

    public function __construct(ManagerInterface $workspaceManager, UserManagerInterface $userManager)
    {
        $this->workspaceManager = $workspaceManager;
        $this->userManager = $userManager;
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        $workspaces = [];
        for ($i = 1; $i <= 5; ++$i) {
            $workspaces[] =
                [
                    'name' => 'Workspace ' . $i,
                    'subdomain' => 'w' . $i,
                    'created' => 'tester' . $i,
                ];
        }
        for ($i = 6; $i <= 10; ++$i) {
            $workspaces[] =
                [
                    'name' => 'Workspace ' . $i,
                    'subdomain' => 'w' . $i,
                    'domain' => $i !== 10 ? 'w' . $i . '.local:8000' : 'w10.workspace.local:8000',
                    'created' => 'tester' . $i,
                ];
        }

        foreach ($workspaces as $w) {
            $workspace = $this->createWorkspace($w);
            // let's make workspace 5 use ES
            if ('Workspace 5' == $workspace->getName()) {
                $workspace->setLanguage(LanguageEnum::ES);
            }
            $this->workspaceManager->update($workspace);
        }
        $this->workspaceManager->flush();
    }

    private function createWorkspace(array $data): Workspace
    {
        $workspace = $this->workspaceManager->create();
        $workspace->setName($data['name']);
        if (isset($data['domain'])) {
            $workspace->setDomain($data['domain']);
        } else {
            $workspace->setSubdomain($data['subdomain']);
        }
        $workspace->setCreatedBy($this->getUser($data['created']));
        $workspace->setUpdatedBy($this->getUser($data['created']));

        return $workspace;
    }

    private function getUser(string $username): User
    {
        return $this->userManager->findUserByUsername($username);
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}

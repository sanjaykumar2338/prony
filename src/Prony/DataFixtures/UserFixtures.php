<?php

declare(strict_types=1);

namespace Prony\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Talav\Component\User\Manager\UserManagerInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    /** @var UserManagerInterface */
    private $userManager;

    /** @var Generator */
    private $faker;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        // Generate regular users
        for ($i = 0; $i < 50; ++$i) {
            $user = $this->userManager->create();
            $user->setUsername($this->faker->userName);
            $user->setEmail($this->faker->email);
            $user->setPlainPassword($this->faker->password);
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setEnabled(true);
            $this->userManager->update($user);
        }

        // Generate workspace owners
        for ($i = 1; $i <= 10; ++$i) {
            $user = $this->userManager->create();
            $user->setUsername('tester' . $i);
            $user->setEmail('tester' . $i . '@test.com');
            $user->setPlainPassword('tester' . $i);
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setEnabled(true);
            $this->userManager->update($user);
        }

        // Generate super admin
        $user = $this->userManager->create();
        $user->setUsername('admin');
        $user->setEmail('admin@test.com');
        $user->setPlainPassword('admin');
        $user->setFirstName($this->faker->firstName);
        $user->setLastName($this->faker->lastName);
        $user->setEnabled(true);
        $this->userManager->update($user);

        $this->userManager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setFirstName('Emma');
        $user1->setLastName('Thomson');
        $user1->setFullName('Emma Thomson');
        $user1->setEmail('ethomson@tomhrm.com');
        $user1->setCreatedAt(new \DateTimeImmutable('now'));

        $user2 = new User();
        $user2->setFirstName('Oliver');
        $user2->setLastName('Sullivan');
        $user2->setFullName('Oliver Sullivan');
        $user2->setEmail('osullivan@tomhrm.com');
        $user2->setCreatedAt(new \DateTimeImmutable('now'));

        $manager->persist($user1);
        $manager->persist($user2);

        $manager->flush();
    }
}

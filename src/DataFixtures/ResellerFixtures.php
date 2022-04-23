<?php

namespace App\DataFixtures;

use App\Factory\ResellerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResellerFixtures extends Fixture
{
    private ObjectManager $manager;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $resellers = ResellerFactory::createMany(5);

        foreach ($resellers as $reseller) {
            $manager->persist($reseller->object());
        }

        $manager->flush();
    }
}

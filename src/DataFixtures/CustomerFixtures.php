<?php

namespace App\DataFixtures;

use App\Factory\CustomerFactory;
use App\Factory\ResellerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
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

        $customers = CustomerFactory::createMany(
            25,
            function() {
                return ['reseller' => ResellerFactory::random()];
            }
        );

        foreach ($customers as $customer) {
            $manager->persist($customer->object());
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ResellerFixtures::class
        ];
    }
}

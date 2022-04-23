<?php

namespace App\DataFixtures;

use App\Factory\BrandFactory;
use App\Factory\PhoneFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PhoneFixtures extends Fixture implements DependentFixtureInterface
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

        $phones = PhoneFactory::createMany(
            20,
            function() {
                return ['brand' => BrandFactory::random()];
            }
        );

        foreach ($phones as $phone) {
            $manager->persist($phone->object());
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BrandFixtures::class,
        ];
    }
}

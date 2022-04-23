<?php

namespace App\DataFixtures;

use App\Factory\BrandFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BrandFixtures extends Fixture
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

        $brands = BrandFactory::createMany(5);

        foreach ($brands as $brand) {
            $manager->persist($brand->object());
        }

        $manager->flush();
    }
}

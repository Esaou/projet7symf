<?php

namespace App\Factory;

use App\Entity\Reseller;
use App\Repository\ResellerRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Reseller>
 *
 * @method static Reseller|Proxy createOne(array $attributes = [])
 * @method static Reseller[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Reseller|Proxy find(object|array|mixed $criteria)
 * @method static Reseller|Proxy findOrCreate(array $attributes)
 * @method static Reseller|Proxy first(string $sortedField = 'id')
 * @method static Reseller|Proxy last(string $sortedField = 'id')
 * @method static Reseller|Proxy random(array $attributes = [])
 * @method static Reseller|Proxy randomOrCreate(array $attributes = [])
 * @method static Reseller[]|Proxy[] all()
 * @method static Reseller[]|Proxy[] findBy(array $attributes)
 * @method static Reseller[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Reseller[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ResellerRepository|RepositoryProxy repository()
 * @method Reseller|Proxy create(array|callable $attributes = [])
 */
final class ResellerFactory extends ModelFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->passwordHasher = $passwordHasher;
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->name(),
            'email' => self::faker()->email(),
            'password' => self::faker()->password(),
        ];
    }

    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function(Reseller $reseller) {
                $reseller->setPassword($this->passwordHasher->hashPassword($reseller, $reseller->getPassword()));
            })
            ;
    }

    protected static function getClass(): string
    {
        return Reseller::class;
    }
}

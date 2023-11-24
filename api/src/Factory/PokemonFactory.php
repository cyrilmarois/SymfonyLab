<?php

namespace App\Factory;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Pokemon>
 *
 * @method        Pokemon|Proxy                     create(array|callable $attributes = [])
 * @method static Pokemon|Proxy                     createOne(array $attributes = [])
 * @method static Pokemon|Proxy                     find(object|array|mixed $criteria)
 * @method static Pokemon|Proxy                     findOrCreate(array $attributes)
 * @method static Pokemon|Proxy                     first(string $sortedField = 'id')
 * @method static Pokemon|Proxy                     last(string $sortedField = 'id')
 * @method static Pokemon|Proxy                     random(array $attributes = [])
 * @method static Pokemon|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PokemonRepository|RepositoryProxy repository()
 * @method static Pokemon[]|Proxy[]                 all()
 * @method static Pokemon[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Pokemon[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Pokemon[]|Proxy[]                 findBy(array $attributes)
 * @method static Pokemon[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Pokemon[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PokemonFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'attack' => self::faker()->numberBetween(1, 32767),
            'attackSpecial' => self::faker()->numberBetween(1, 32767),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'defense' => self::faker()->numberBetween(1, 32767),
            'defenseSpecial' => self::faker()->numberBetween(1, 32767),
            'generation' => self::faker()->numberBetween(1, 32767),
            'health' => self::faker()->numberBetween(1, 32767),
            'legendary' => self::faker()->boolean(),
            'name' => self::faker()->text(255),
            'speed' => self::faker()->numberBetween(1, 32767),
            'total' => self::faker()->numberBetween(1, 32767),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Pokemon $pokemon): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Pokemon::class;
    }
}

<?php

namespace App\DataFixtures;

use App\Factory\PokemonFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        PokemonFactory::createMany(40);

        $manager->flush();
    }
}

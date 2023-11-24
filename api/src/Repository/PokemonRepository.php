<?php

namespace App\Repository;

use App\Entity\Pokemon;
use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @extends ServiceEntityRepository<Pokemon>
 *
 * @method Pokemon|null create(array $data)
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
    ) {
        $this->cache = new FilesystemAdapter();
        $this->typeRepository = $registry->getRepository(Type::class);
        parent::__construct($registry, Pokemon::class);
    }

    public function import(array $data): void
    {
        $total = count($data);
        foreach ($data as $i => $datum) {
            // last line is empty
            if ($i === $total - 1) {
                break;
            }
            $pokemon = new Pokemon();
            $pokemon->name = strtolower($datum['Name']);
            $pokemon->total = $datum['Total'];
            $pokemon->health = $datum['HP'];
            $pokemon->attack = $datum['Attack'];
            $pokemon->defense = $datum['Defense'];
            $pokemon->attackSpecial = $datum['Sp. Atk'];
            $pokemon->defenseSpecial = $datum['Sp. Def'];
            $pokemon->speed = $datum['Speed'];
            $pokemon->generation = $datum['Generation'];
            $pokemon->legendary = filter_var($datum['Legendary'], FILTER_VALIDATE_BOOLEAN);     // ($datum['Legendary'] === 'True');
            if (!empty($datum['Type 1'])) {
                $type = $this->getPokemonType($datum['Type 1']);
                $pokemon->types->add($type);
            }
            if (!empty($datum['Type 2'])) {
                $type = $this->getPokemonType($datum['Type 2']);
                $pokemon->types->add($type);
            }
            $this->getEntityManager()->persist($pokemon);
            $this->getEntityManager()->flush();
        }
    }

    // cache result to prevent fetching db everytime we import a pokemon
    private function getPokemonType(string $type): Type
    {
        $this->cache = new FilesystemAdapter();
        $type = strtolower($type);
        return $this->cache->get($type, function (ItemInterface $item) use ($type) {
            $item->expiresAfter(3600);
            $typeEntity = $this->typeRepository->findOneBy(['slug' => $type]);
            if (!$typeEntity instanceof Type) {
                $typeEntity = new Type();
                $typeEntity->slug = $type;
                $this->getEntityManager()->persist($typeEntity);
                $this->getEntityManager()->flush();
            }

            return $typeEntity;
        });
    }
}

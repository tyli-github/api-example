<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const REF_ADVENTURE = 'adventure';
    public const REF_SCIFI = 'scifi';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getNames() as $reference => $name) {
            $category = new Category();
            $category->setName($name);

            $this->addReference($reference, $category);

            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    private function getNames(): array
    {
        return [
            self::REF_ADVENTURE => 'Adventure',
            self::REF_SCIFI => 'Sci-Fi',
        ];
    }
}

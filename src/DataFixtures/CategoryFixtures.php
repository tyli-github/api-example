<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getNames() as $name) {
            $category = new Category();
            $category->setName('Sci-Fi');

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
            'Adventure',
            'Sci-Fi',
        ];
    }
}

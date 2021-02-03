<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getMovieData() as $categoryRef => $movieNames) {
            foreach ($movieNames as $movieName) {
                /** @var Category $category */
                $category = $this->getReference($categoryRef);

                $movie = new Movie();
                $movie->setName($movieName);
                $movie->setYear(mt_rand(1950, 2010));
                $movie->setCategory($category);

                $manager->persist($movie);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }

    private function getMovieData(): array
    {
        return [
            CategoryFixtures::REF_SCIFI => [
                'Back To The Future.',
                'Terminator 2: Judgment Day.',
                'Star Wars Episode IV: A New Hope.',
                '2001: A Space Odyssey.',
                'Matrix, The.',
                'Star Wars Episode V: The Empire Strikes Back.',
                'Alien.',
                'Blade Runner.',
            ],
            CategoryFixtures::REF_ADVENTURE => [
                'Indiana Jones And The Temple Of Doom',
                'Avatar',
                'Jaws',
                'The Great Escape',
                'Indiana Jones And The Last Crusade',
                'Avengers: Endgame',
                'Seven Samurai',
                'Star Wars: Episode IV - A New Hope',
                'Spirited Away',
                'Interstellar',
                'Star Wars: Episode V - The Empire Strikes Back',
                'Inception',
                'The Lord Of The Rings: The Return Of The King',
            ],
        ];
    }
}
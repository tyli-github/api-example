<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Movie;
use Codeception\Test\Unit;
use Doctrine\Common\Collections\ArrayCollection;

class CategoryTest extends Unit
{
    private Category $category;

    protected function _before(): void
    {
        $this->category = new Category();
    }

    public function testCategoryInstantiationWithDefaults()
    {
        $this->assertNull($this->category->getId());
        $this->assertNull($this->category->getName());
        $this->assertInstanceOf(ArrayCollection::class, $this->category->getMovies());
        $this->assertCount(0, $this->category->getMovies());
    }

    public function testSetAndGetName()
    {
        $this->category->setName('Sci-Fi');
        $this->assertSame('Sci-Fi', $this->category->getName());
    }

    public function testMoviesCollectionIsInitialized()
    {
        $movies = $this->category->getMovies();
        $this->assertInstanceOf(ArrayCollection::class, $movies);
        $this->assertTrue($movies->isEmpty());
    }

    public function testAddMovieToCollection()
    {
        $movie = new Movie();
        $movie->setName('The Matrix');
        $movie->setYear(1999);
        $movie->setCategory($this->category);

        // Note: Direct collection manipulation not saved, only for demonstration purposes
        $this->category->getMovies()->add($movie);

        $this->assertCount(1, $this->category->getMovies());
        $this->assertTrue($this->category->getMovies()->contains($movie));
    }

    public function testMultipleMoviesInCollection()
    {
        $movie1 = new Movie();
        $movie1->setName('The Matrix');
        $movie1->setCategory($this->category);

        $movie2 = new Movie();
        $movie2->setName('Avatar');
        $movie2->setCategory($this->category);

        $this->category->getMovies()->add($movie1);
        $this->category->getMovies()->add($movie2);

        $this->assertCount(2, $this->category->getMovies());
    }

    public function testCategoryFluentInterface()
    {
        $result = $this->category->setName('Adventure');

        $this->assertSame($this->category, $result);
        $this->assertSame('Adventure', $this->category->getName());
    }
}

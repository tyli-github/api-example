<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\MovieFixtures;
use App\Tests\FunctionalTester;

class MovieCrudCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->loadFixtures([CategoryFixtures::class, MovieFixtures::class]);
    }

    public function testGetMoviesCollection(FunctionalTester $I): void
    {
        $I->sendGet('/movies');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['member' => []]);
    }

    public function testGetSingleMovie(FunctionalTester $I): void
    {
        $I->sendGet('/movies');
        $data = json_decode($I->grabResponse(), true);
        $id = $data['member'][0]['id'];

        $I->sendGet('/movies/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['id' => $id]);
    }

    public function testPostMovie(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/ld+json');
        $I->sendPost('/movies', json_encode(['name' => 'Test Movie', 'year' => 2000]));
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson(['name' => 'Test Movie', 'year' => 2000]);
    }

    public function testPostMovieValidationFails(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/ld+json');
        $I->sendPost('/movies', json_encode(['name' => '', 'year' => 1700]));
        $I->seeResponseCodeIs(422);
    }

    public function testPutMovie(FunctionalTester $I): void
    {
        $I->sendGet('/movies');
        $data = json_decode($I->grabResponse(), true);
        $id = $data['member'][0]['id'];

        $I->haveHttpHeader('Content-Type', 'application/ld+json');
        $I->sendPut('/movies/' . $id, json_encode(['name' => 'Updated Movie', 'year' => 1999]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['name' => 'Updated Movie']);
    }

    public function testDeleteMovie(FunctionalTester $I): void
    {
        $I->sendGet('/movies');
        $data = json_decode($I->grabResponse(), true);
        $id = $data['member'][0]['id'];

        $I->sendDelete('/movies/' . $id);
        $I->seeResponseCodeIs(204);

        $I->sendGet('/movies/' . $id);
        $I->seeResponseCodeIs(404);
    }

    public function testPaginationResponse(FunctionalTester $I): void
    {
        $I->sendGet('/movies');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['totalItems' => 21]);
    }

    public function testSearchFilter(FunctionalTester $I): void
    {
        $I->sendGet('/movies?name=Inception');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}

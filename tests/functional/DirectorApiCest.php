<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

class DirectorApiCest
{
    public function testGetDirectorsCollection(FunctionalTester $I): void
    {
        $I->sendGet('/directors');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            ['id' => 1, 'name' => 'Steven Spielberg'],
            ['id' => 2, 'name' => 'Jon Favreau'],
        ]);
    }

    public function testGetDirectorItem(FunctionalTester $I): void
    {
        $I->sendGet('/directors/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['id' => 1, 'name' => 'Steven Spielberg']);
    }

    public function testGetDirectorNotFound(FunctionalTester $I): void
    {
        $I->sendGet('/directors/999');
        $I->seeResponseCodeIs(404);
    }

    public function testPostDirector(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/ld+json');
        $I->sendPost('/directors', json_encode(['name' => 'Christopher Nolan']));
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson(['name' => 'Christopher Nolan']);
    }

    public function testPostDirectorValidationFails(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/ld+json');
        $I->sendPost('/directors', json_encode(['name' => '']));
        $I->seeResponseCodeIs(422);
    }

    public function testDeleteDirector(FunctionalTester $I): void
    {
        $I->sendDelete('/directors/1');
        $I->seeResponseCodeIs(204);
    }
}

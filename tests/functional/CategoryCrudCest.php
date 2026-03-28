<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\CategoryFixtures;
use App\Tests\FunctionalTester;

class CategoryCrudCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->loadFixtures([CategoryFixtures::class]);
    }

    public function testGetCategoriesCollection(FunctionalTester $I): void
    {
        $I->sendGet('/categories');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['member' => []]);
    }

    public function testGetSingleCategory(FunctionalTester $I): void
    {
        $I->sendGet('/categories');
        $data = json_decode($I->grabResponse(), true);
        $id = $data['member'][0]['id'];

        $I->sendGet('/categories/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['id' => $id]);
    }

    public function testPostCategory(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/ld+json');
        $I->sendPost('/categories', json_encode(['name' => 'Horror']));
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson(['name' => 'Horror']);
    }

    public function testPostCategoryValidationFails(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/ld+json');
        $I->sendPost('/categories', json_encode(['name' => '']));
        $I->seeResponseCodeIs(422);
    }

    public function testPutCategory(FunctionalTester $I): void
    {
        $I->sendGet('/categories');
        $data = json_decode($I->grabResponse(), true);
        $id = $data['member'][0]['id'];

        $I->haveHttpHeader('Content-Type', 'application/ld+json');
        $I->sendPut('/categories/' . $id, json_encode(['name' => 'Updated Category']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['name' => 'Updated Category']);
    }

    public function testDeleteCategory(FunctionalTester $I): void
    {
        $I->sendGet('/categories');
        $data = json_decode($I->grabResponse(), true);
        $id = $data['member'][0]['id'];

        $I->sendDelete('/categories/' . $id);
        $I->seeResponseCodeIs(204);

        $I->sendGet('/categories/' . $id);
        $I->seeResponseCodeIs(404);
    }
}

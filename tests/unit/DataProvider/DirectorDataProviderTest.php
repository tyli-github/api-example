<?php

declare(strict_types=1);

namespace App\Tests\Unit\DataProvider;

use ApiPlatform\Metadata\Operation;
use App\DataProvider\DirectorDataProvider;
use App\Entity\Director;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;

class DirectorDataProviderTest extends Unit
{
    private DirectorDataProvider $dataProvider;

    protected function _before(): void
    {
        $this->dataProvider = new DirectorDataProvider();
    }

    public function testProvideCollectionForDirector()
    {
        /** @var Operation $operation */
        $operation = $this->createMockOperation(Director::class);

        $result = $this->dataProvider->provide($operation, []);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(Director::class, $result[0]);
        $this->assertSame('Steven Spielberg', $result[0]->getName());
        $this->assertSame('Jon Favreau', $result[1]->getName());
    }

    public function testProvideItemForDirector()
    {
        /** @var Operation $operation */
        $operation = $this->createMockOperation(Director::class);

        $result = $this->dataProvider->provide($operation, ['id' => 1]);

        $this->assertInstanceOf(Director::class, $result);
        $this->assertSame(1, $result->getId());
        $this->assertSame('Steven Spielberg', $result->getName());
    }

    public function testProvideItemNotFound()
    {
        /** @var Operation $operation */
        $operation = $this->createMockOperation(Director::class);

        $result = $this->dataProvider->provide($operation, ['id' => 999]);

        $this->assertNull($result);
    }

    public function testProvideIgnoresNonDirectorClass()
    {
        /** @var Operation $operation */
        $operation = $this->createMockOperation('stdClass');

        $result = $this->dataProvider->provide($operation, []);

        $this->assertNull($result);
    }

    private function createMockOperation(string $class): MockObject
    {
        $operation = $this->createMock(Operation::class);
        $operation->method('getClass')->willReturn($class);

        return $operation;
    }
}

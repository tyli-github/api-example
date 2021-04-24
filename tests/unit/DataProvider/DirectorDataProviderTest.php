<?php

declare(strict_types=1);

namespace App\Tests\Unit\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\DirectorDataProvider;
use App\Entity\Director;
use Codeception\Test\Unit;
use stdClass;

class DirectorDataProviderTest extends Unit
{
    /**
     * @dataProvider supportsProvider
     */
    public function testSupports(string $class, bool $expected)
    {
        $dataProvider = new DirectorDataProvider();

        $this->assertSame($expected, $dataProvider->supports($class));
    }

    public function supportsProvider(): iterable
    {
        yield 'Director entity supported' => [Director::class, true];
        yield 'stdClass not supported' => [stdClass::class, false];
    }

    public function testGetCollection()
    {
        $dataProvider = new DirectorDataProvider();
        $data = $dataProvider->getCollection('not_used');

        $this->assertCount(2, $data);
        $this->assertInstanceOf(Director::class, current($data));
    }

    /**
     * @param string $id
     * @param string|null $class
     * @throws ResourceClassNotSupportedException
     * @dataProvider itemProvider
     */
    public function testGetItem(string $id, ?string $class)
    {
        $dataProvider = new DirectorDataProvider();

        if ($class === null) {
            $this->assertNull($dataProvider->getItem('not_used', $id));
        } else {
            $this->assertInstanceOf($class, $dataProvider->getItem('not_used', $id));
        }
    }

    public function itemProvider(): iterable
    {
        yield 'director id `abc123` exist' => ['abc123', Director::class];
        yield 'director id `def456` exist' => ['def456', Director::class];
        yield 'director id `xxx` does not exist' => ['xxx', null];
    }
}

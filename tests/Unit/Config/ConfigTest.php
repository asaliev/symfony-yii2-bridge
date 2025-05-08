<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Config;

use Asaliev\Yii2Bridge\Config\Config;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testAllReturnsAllValues(): void
    {
        $config = new Config(['foo' => 'bar', 'baz' => 'qux']);
        $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $config->all());
    }

    public function testGetSuppressesExceptionFromGetOrFail(): void
    {
        $configMock = $this->getMockBuilder(Config::class)
            ->setConstructorArgs([['foo' => 'bar']])
            ->onlyMethods(['getOrFail'])
            ->getMock();
        $configMock->method('getOrFail')->willThrowException(new InvalidArgumentException());

        $this->assertEquals('default', $configMock->get('baz', 'default'));
    }

    public function testGetReturnsDefaultValueWhenKeyNotFound(): void
    {
        $config = new Config(['foo' => 'bar']);
        $this->assertEquals('default', $config->get('baz', 'default'));
    }

    public function testGetOrFailThrowsExceptionWhenKeyNotFound(): void
    {
        $config = new Config(['foo' => 'bar']);
        $this->expectException(InvalidArgumentException::class);
        $config->getOrFail('baz');
    }

    public function testGetNestedValueUsingDotNotation(): void
    {
        $config = new Config(['foo' => ['bar' => 'baz']]);
        $this->assertEquals('baz', $config->get('foo.bar'));
        $this->assertEquals('baz', $config->getOrFail('foo.bar'));
    }
}

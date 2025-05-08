<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Application;

use Asaliev\Yii2Bridge\Application\ContainerFactory;
use Asaliev\Yii2Bridge\Config\Config;
use Asaliev\Yii2Bridge\Exception\ContainerException;
use PHPUnit\Framework\TestCase;
use stdClass;
use yii\di\Container as YiiContainer;

class ContainerFactoryTest extends TestCase
{
    public function createsContainerWithValidClass(): void
    {
        $configMock = $this->createMock(Config::class);
        $configMock->method('getOrFail')->with('container')->willReturn([]);

        $factory = new ContainerFactory($configMock);
        $factory->createContainer();
        $this->expectNotToPerformAssertions();
    }

    public function testThrowsExceptionWhenClassDoesNotExist(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Class Foo\NonExistentClass does not exist.');

        $configMock = $this->createMock(Config::class);

        $factory = new ContainerFactory($configMock);
        $factory->createContainer('Foo\NonExistentClass'); // @phpstan-ignore-line
    }

    public function testThrowsExceptionWhenClassIsNotYiiContainer(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Container class must be an instance of yii\di\Container');

        $configMock = $this->createMock(Config::class);

        $factory = new ContainerFactory($configMock);
        $factory->createContainer(stdClass::class);
    }

    public function testCreatesContainerWithCustomSubclass(): void
    {
        $customContainerClass = new class([]) extends YiiContainer {};
        $configMock = $this->createMock(Config::class);
        $configMock->method('getOrFail')->with('container')->willReturn([]);

        $factory = new ContainerFactory($configMock);
        /** @var class-string $customerContainerClassName */
        $customerContainerClassName = get_class($customContainerClass);
        $container = $factory->createContainer($customerContainerClassName);

        $this->assertInstanceOf(get_class($customContainerClass), $container);
    }
}

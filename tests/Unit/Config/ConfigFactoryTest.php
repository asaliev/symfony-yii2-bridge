<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Config;

use Asaliev\Yii2Bridge\Config\ConfigFactory;
use Symfony\Component\Config\Loader\LoaderInterface;
use PHPUnit\Framework\TestCase;

class ConfigFactoryTest extends TestCase
{
    public function testCreateCallsLoader(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())
            ->method('load')
            ->with('config.php')
            ->willReturn(['foo' => 'bar']);

        $factory = new ConfigFactory($loader);
        $config = $factory->create('config.php');

        $this->assertEquals(['foo' => 'bar'], $config->all());
    }
}

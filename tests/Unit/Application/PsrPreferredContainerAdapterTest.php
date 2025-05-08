<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Application;

use Asaliev\Yii2Bridge\Application\PsrPreferredContainerAdapter;
use Asaliev\Yii2Bridge\Config\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class PsrPreferredContainerAdapterTest extends TestCase
{
    private ?Container $psrContainer;

    private ?Config $config;

    private ?object $fromPsrContainer;

    private ?object $fromYiiContainer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->psrContainer = new Container();
        $this->fromPsrContainer = new \stdClass();
        $this->psrContainer->set('foo', $this->fromPsrContainer);
        $this->psrContainer->set('onlyInPsr', $this->fromPsrContainer);

        $this->fromYiiContainer = new \stdClass();
        $this->config = new Config([
            'container' => [
                'definitions' => [
                    'foo' => $this->fromYiiContainer,
                    'onlyInYii' => $this->fromYiiContainer,
                ],
            ],
        ]);
    }

    protected function tearDown(): void
    {
        $this->psrContainer->reset();
        $this->psrContainer = null;
        $this->fromPsrContainer = null;
        $this->fromYiiContainer = null;
        $this->config = null;

        parent::tearDown();
    }

    public function testHasFromPsrContainer(): void
    {
        $adapter = new PsrPreferredContainerAdapter($this->psrContainer, new Config([]));

        $this->assertTrue($adapter->has('onlyInPsr'));
    }

    public function testHasFromYiiContainer(): void
    {

        $adapter = new PsrPreferredContainerAdapter($this->psrContainer, $this->config);

        $this->assertTrue($adapter->has('onlyInYii'));
    }

    public function testGetFromPsrContainer(): void
    {
        $adapter = new PsrPreferredContainerAdapter($this->psrContainer, $this->config);

        $this->assertSame($this->fromPsrContainer, $adapter->get('foo'));
    }

    public function testFallbackToYiiContainer(): void
    {
        $this->psrContainer->reset();
        $adapter = new PsrPreferredContainerAdapter($this->psrContainer, $this->config);

        $this->assertSame($this->fromYiiContainer, $adapter->get('foo'));
    }

    public function testNotFoundExceptionConvertedToInvalidConfigException(): void
    {
        $this->expectException(InvalidConfigException::class);

        $psrContainer = $this->createPartialMock(Container::class, ['has', 'get']);
        $psrContainer->expects($this->once())
            ->method('has')
            ->with('non_existent_service')
            ->willReturn(true);
        $psrContainer->expects($this->once())
            ->method('get')
            ->with('non_existent_service')
            ->willThrowException(new ServiceNotFoundException('non_existent_service'));

        $adapter = new PsrPreferredContainerAdapter($psrContainer, new Config([]));

        $adapter->get('non_existent_service');
    }

    public function testContainerExceptionConvertedToInvalidConfigException(): void
    {
        $this->expectException(NotInstantiableException::class);

        $psrContainer = $this->createPartialMock(Container::class, ['has', 'get']);
        $psrContainer->expects($this->once())
            ->method('has')
            ->with('bad_service')
            ->willReturn(true);
        $psrContainer->expects($this->once())
            ->method('get')
            ->with('bad_service')
            ->willThrowException(new RuntimeException('bad_service'));

        $adapter = new PsrPreferredContainerAdapter($psrContainer, new Config([]));

        $adapter->get('bad_service');
    }
}

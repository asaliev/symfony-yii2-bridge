<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Application;

use Asaliev\Yii2Bridge\Application\WebApplicationProvider;
use Asaliev\Yii2Bridge\Config\Config;
use Asaliev\Yii2Bridge\Event\WebApplicationBeforeInitializeEvent;
use Asaliev\Yii2Bridge\Event\WebApplicationInitializedEvent;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use yii\web\Application as WebApplication;

class WebApplicationProviderTest extends TestCase
{
    /**
     * @var string[]|null
     */
    private ?array $yiiConfig;

    private ?Config $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->yiiConfig = require dirname(__DIR__, 2) . '/app/config/web.php';
        $this->config = new Config($this->yiiConfig);
    }

    protected function tearDown(): void
    {
        $this->yiiConfig = null;
        $this->config = null;

        parent::tearDown();
    }

    public function testDispatchesEventsInCorrectOrder(): void
    {
        $dispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $dispatcherMock->expects($this->exactly(2))
            ->method('dispatch')
            ->willReturnCallback(function ($event) {
                if ($event instanceof WebApplicationBeforeInitializeEvent) {
                    $this->assertEquals($this->yiiConfig, $event->getConfig());
                } elseif ($event instanceof WebApplicationInitializedEvent) {
                    $this->assertInstanceOf(WebApplication::class, $event->getApplication());
                    $this->assertEquals('test-app', $event->getApplication()->id);
                    $this->assertEquals($this->yiiConfig, $event->getConfig());
                }
            });

        $provider = new WebApplicationProvider($this->config, $dispatcherMock);
        $application = $provider->provide();
        $this->assertEquals('test-app', $application->id);
    }

    public function testBeforeInitializeEventCanModifyConfig(): void
    {
        $dispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $dispatcherMock->expects($this->exactly(2))
            ->method('dispatch')
            ->willReturnCallback(function ($event) {
                if ($event instanceof WebApplicationBeforeInitializeEvent) {
                    $config = $event->getConfig();
                    $config['id'] = 'test-app2';
                    $event->setConfig($config);
                } elseif ($event instanceof WebApplicationInitializedEvent) {
                    $this->assertArrayHasKey('id', $event->getConfig());
                    $this->assertEquals('test-app2', $event->getConfig()['id']);
                }
            });

        $provider = new WebApplicationProvider($this->config, $dispatcherMock);
        $application = $provider->provide();
        $this->assertEquals('test-app2', $application->id);
    }
}

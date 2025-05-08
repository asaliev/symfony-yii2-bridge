<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\EventListener;

use Asaliev\Tests\Yii2Bridge\Unit\TestCase;
use Asaliev\Yii2Bridge\Event\WebApplicationBeforeInitializeEvent;
use Asaliev\Yii2Bridge\EventListener\YiiContainerConfigListener;
use Yii;
use yii\di\Container;

class YiiContainerConfigListenerTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $listener = new YiiContainerConfigListener();
        $subscribedEvents = $listener::getSubscribedEvents();

        $this->assertArrayHasKey(WebApplicationBeforeInitializeEvent::class, $subscribedEvents);
        $this->assertEquals('onConfigProcessing', $subscribedEvents[WebApplicationBeforeInitializeEvent::class]);
    }

    public function testOnConfigProcessingUpdatesContainerInstanceAndRemovesConfig(): void
    {
        $container = $this->createMock(Container::class);
        $event = new WebApplicationBeforeInitializeEvent(['container' => ['definitions' => ['foo' => 'bar']]]);

        $listener = new YiiContainerConfigListener($container);
        $listener->onConfigProcessing($event);

        $this->assertSame($container, Yii::$container);
        $this->assertArrayNotHasKey('container', $event->getConfig());
    }

    public function testOnConfigProcessingNoOperation(): void
    {
        $listener = new YiiContainerConfigListener(null);
        $event = new WebApplicationBeforeInitializeEvent(['container' => ['definitions' => ['foo' => 'bar']]]);
        $listener->onConfigProcessing($event);

        $this->assertNull(Yii::$container);
        $this->assertEquals(['container' => ['definitions' => ['foo' => 'bar']]], $event->getConfig());
    }
}

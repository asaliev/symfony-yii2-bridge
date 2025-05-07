<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Event;

use Asaliev\Yii2Bridge\Event\WebApplicationBeforeInitializeEvent;
use PHPUnit\Framework\TestCase;

class WebApplicationBeforeInitializeEventTest extends TestCase
{
    public function testGetConfigReturnsInitialConfig(): void
    {
        $initialConfig = [
            'foo' => [
                'bar' => 'baz',
            ],
        ];
        $event = new WebApplicationBeforeInitializeEvent($initialConfig);

        $this->assertSame($initialConfig, $event->getConfig());
    }

    public function testSetConfigUpdatesConfig(): void
    {
        $initialConfig = [
            'foo' => [
                'bar' => 'baz',
            ],
        ];
        $updatedConfig = [
            'foo' => [
                'bar' => 'qux',
            ],
        ];
        $event = new WebApplicationBeforeInitializeEvent($initialConfig);
        $event->setConfig($updatedConfig);

        $this->assertSame($updatedConfig, $event->getConfig());
    }
}

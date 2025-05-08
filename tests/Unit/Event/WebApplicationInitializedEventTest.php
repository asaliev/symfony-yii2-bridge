<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Event;

use Asaliev\Yii2Bridge\Event\WebApplicationInitializedEvent;
use PHPUnit\Framework\TestCase;
use yii\base\Application;

class WebApplicationInitializedEventTest extends TestCase
{
    public function testGetApplicationReturnsApplicationInstance(): void
    {
        $app = $this->createMock(Application::class);
        $config = ['id' => 'test-app'];

        $event = new WebApplicationInitializedEvent($app, $config);

        $this->assertSame($app, $event->getApplication());
    }

    public function testGetConfigReturnsOriginalConfig(): void
    {
        $app = $this->createMock(Application::class);
        $config = ['id' => 'test-app'];

        $event = new WebApplicationInitializedEvent($app, $config);

        $this->assertSame($config, $event->getConfig());
    }
}

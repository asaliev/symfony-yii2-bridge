<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Messages;

use Asaliev\Yii2Bridge\Messages\RunApplicationMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use yii\base\Application;

class RunApplicationMessageTest extends TestCase
{
    public function testGetApplication(): void
    {
        $app = $this->createMock(Application::class);
        $request = $this->createMock(Request::class);
        $message = new RunApplicationMessage($request, $app);

        $this->assertSame($app, $message->getApplication());
    }

    public function testGetRequest(): void
    {
        $app = $this->createMock(Application::class);
        $request = $this->createMock(Request::class);
        $message = new RunApplicationMessage($request, $app);

        $this->assertSame($request, $message->getRequest());
    }
}

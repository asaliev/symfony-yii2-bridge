<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Controllers;

use Asaliev\Yii2Bridge\Controller\DispatcherController;
use Asaliev\Yii2Bridge\Exception\DispatcherException;
use Asaliev\Yii2Bridge\Messages\RunApplicationMessage;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use yii\base\Application;

class DispatcherControllerTest extends TestCase
{
    public function testRunDispatchesRunApplicationMessage(): void
    {
        $expectedResponse = new Response('foobar');

        $yiiMock = $this->createMock(Application::class);
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus
            ->method('dispatch')
            ->willReturnCallback(function ($message) use ($expectedResponse) {
                return new Envelope(
                    $message,
                    [new HandledStamp($expectedResponse, 'alias')]
                );
            });

        $controller = new DispatcherController($messageBus, $yiiMock);

        $response = $controller->run(new Request());
        $this->assertSame($expectedResponse, $response);
    }

    public function testRunThrowsExceptionWhenDispatchFails(): void
    {
        $yiiMock = $this->createMock(Application::class);
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus
            ->method('dispatch')
            ->willThrowException(new Exception('foobar'));

        $controller = new DispatcherController($messageBus, $yiiMock);

        $this->expectException(DispatcherException::class);
        $this->expectExceptionMessage('Failed to handle the message: ' . RunApplicationMessage::class);

        $controller->run(new Request());
    }

    public function testRunThrowsExceptionOnInvalidResponse(): void
    {
        $expectedResponse = [];

        $yiiMock = $this->createMock(Application::class);
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus
            ->method('dispatch')
            ->willReturnCallback(function ($message) use ($expectedResponse) {
                return new Envelope(
                    $message,
                    [new HandledStamp($expectedResponse, 'alias')]
                );
            });

        $controller = new DispatcherController($messageBus, $yiiMock);

        $this->expectException(DispatcherException::class);
        $this->expectExceptionMessage('Expected \Symfony\Component\HttpFoundation\Response, got: array');

        $controller->run(new Request());
    }
}

<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Messages\Handlers;

use Asaliev\Yii2Bridge\Http\ResponseAdapterInterface;
use Asaliev\Yii2Bridge\Messages\Handlers\YiiDispatcherHandler;
use Asaliev\Yii2Bridge\Messages\RunApplicationMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use yii\base\Application;
use yii\web\Response;

class YiiDispatcherHandlerTest extends TestCase
{
    /**
     * @var (Application&MockObject)|null
     */
    private ?Application $app;

    /**
     * @var (ResponseAdapterInterface&MockObject)|null
     */
    private ?ResponseAdapterInterface $responseAdapter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createPartialMock(Application::class, ['run', 'getResponse', 'handleRequest']);
        $this->responseAdapter = $this->createPartialMock(ResponseAdapterInterface::class, ['toSymfonyResponse']);
    }

    protected function tearDown(): void
    {
        $this->app = null;
        $this->responseAdapter = null;

        parent::tearDown();
    }

    public function testInvokesApplicationAndReturnsSymfonyResponse(): void
    {
        $request = $this->createMock(Request::class);
        $yiiResponse = $this->createMock(Response::class);
        $message = new RunApplicationMessage($request, $this->app);

        $yiiHtmlOutput = 'Output from app->run()';
        $this->app->method('run')->willReturnCallback(function () use ($yiiHtmlOutput) {
            echo $yiiHtmlOutput;
        });
        $this->app->method('getResponse')->willReturn($yiiResponse);

        $this->responseAdapter->method('toSymfonyResponse')->with($yiiResponse, $yiiHtmlOutput)
            ->willReturn(new SymfonyResponse('foobar'));

        $handler = new YiiDispatcherHandler($this->responseAdapter);

        $response = $handler($message);

        $this->assertEquals('foobar', $response->getContent());
    }
}

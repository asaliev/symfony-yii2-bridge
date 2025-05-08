<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Http;

use Asaliev\Yii2Bridge\Http\ResponseAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use yii\base\Response as YiiBaseResponse;
use yii\web\Cookie as YiiCookie;
use yii\web\HeaderCollection;
use yii\web\Response as YiiWebResponse;

class ResponseAdapterTest extends TestCase
{
    public function testToSymfonyResponseConvertsFromWebResponse(): void
    {
        $yiiResponse = $this->createMock(YiiWebResponse::class);
        $headers = new HeaderCollection();
        $headers->add('Content-Type', 'application/json');
        $yiiResponse->method('getHeaders')->willReturn($headers);
        $yiiResponse->method('getStatusCode')->willReturn(200);
        $yiiResponse->method('getCookies')->willReturn([]);

        $adapter = new ResponseAdapter();
        $symfonyResponse = $adapter->toSymfonyResponse($yiiResponse, 'output');

        $this->assertEquals(200, $symfonyResponse->getStatusCode());
        $this->assertEquals('application/json', $symfonyResponse->headers->get('Content-Type'));
        $this->assertEquals('output', $symfonyResponse->getContent());
    }

    public function testToSymfonyResponseConvertsFromWebResponseWithCookies(): void
    {
        $yiiResponse = $this->createMock(YiiWebResponse::class);
        $yiiResponse->method('getHeaders')->willReturn(new HeaderCollection());
        $yiiResponse->method('getStatusCode')->willReturn(200);

        $yiiCookie = new YiiCookie([
            'name' => 'test',
            'value' => 'value',
            'expire' => time() + 3600,
            'path' => '/',
            'domain' => 'example.com',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => 'lax',
        ]);
        $yiiResponse->method('getCookies')->willReturn([$yiiCookie]);

        $adapter = new ResponseAdapter();
        $symfonyResponse = $adapter->toSymfonyResponse($yiiResponse, 'output');

        $this->assertCount(1, $symfonyResponse->headers->getCookies());
        $cookie = $symfonyResponse->headers->getCookies()[0];
        $this->assertEquals('test', $cookie->getName());
        $this->assertEquals('value', $cookie->getValue());
        $this->assertEquals('example.com', $cookie->getDomain());
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertEquals('lax', $cookie->getSameSite());
    }

    public function testToSymfonyResponseConvertsFromBaseResponse(): void
    {
        $yiiResponse = $this->createMock(YiiBaseResponse::class);
        $yiiResponse->exitStatus = 404;

        $adapter = new ResponseAdapter();
        $symfonyResponse = $adapter->toSymfonyResponse($yiiResponse, 'not found');

        $this->assertEquals(404, $symfonyResponse->getStatusCode());
        $this->assertEquals('not found', $symfonyResponse->getContent());
    }
}

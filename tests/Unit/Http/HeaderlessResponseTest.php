<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Http;

use Asaliev\Yii2Bridge\Http\HeaderlessResponse;
use PHPUnit\Framework\TestCase;
use yii\web\Response;
use yii\web\ResponseFormatterInterface;

class HeaderlessResponseTest extends TestCase
{
    public function testSendHeaders(): void
    {
        $formatter = $this->createMock(ResponseFormatterInterface::class);
        $formatter->expects($this->any())->method('format');

        $response = $this->createPartialMock(HeaderlessResponse::class, ['init']);
        $response->formatters = [Response::FORMAT_HTML => $formatter];
        $response->format = Response::FORMAT_HTML;

        ob_start();
        $response->send();
        $output = ob_get_clean();

        $headers = headers_list();
        $this->assertEmpty($output, 'Expected no output to be echoed.');
        $this->assertEmpty($headers, 'Expected no headers to be sent.');
    }
}

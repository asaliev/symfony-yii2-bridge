<?php

namespace Asaliev\Tests\Yii2Bridge\Unit\Config\Loader;

use Asaliev\Yii2Bridge\Config\Loader\PhpArrayLoader;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PhpArrayLoaderTest extends TestCase
{
    public function testSupportsPhpFiles(): void
    {
        $loader = new PhpArrayLoader();
        $this->assertTrue($loader->supports(TEST_APP_DIR . '/config/web.php'));
    }

    public function testDoesNotSupportOtherFiles(): void
    {
        $loader = new PhpArrayLoader();
        $this->assertFalse($loader->supports(TEST_APP_DIR . '/config/other/config.yaml'));
        $this->assertFalse($loader->supports(TEST_APP_DIR . '/config/other/config.xml'));
    }

    public function testThrowsExceptionWhenFileDoesNotExist(): void
    {
        $file = TEST_APP_DIR . '/config/nonexistent.php';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File does not exist: ' . $file);

        $loader = new PhpArrayLoader();
        $loader->load($file);
    }

    public function testThrowsExceptionWhenFileDoesNotReturnArray(): void
    {
        $file = TEST_APP_DIR . '/config/other/config_returns_string.php';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Config file must return an array: ' . $file);

        $loader = new PhpArrayLoader();
        $loader->load($file);
    }

    public function testLoadsPhpArrayFile(): void
    {
        $file = TEST_APP_DIR . '/config/web.php';

        $loader = new PhpArrayLoader();
        $config = $loader->load($file);

        $this->assertArrayHasKey('id', $config);
        $this->assertEquals('test-app', $config['id']);
    }
}

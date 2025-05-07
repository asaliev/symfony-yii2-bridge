<?php

namespace Asaliev\Tests\Yii2Bridge\Unit;

use Asaliev\Yii2Bridge\DependencyInjection\Yii2Extension;
use Asaliev\Yii2Bridge\Yii2Bundle;
use PHPUnit\Framework\TestCase;

class Yii2BundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new Yii2Bundle();
        $extension = $bundle->getContainerExtension();

        $this->assertInstanceOf(Yii2Extension::class, $extension);
    }
}
